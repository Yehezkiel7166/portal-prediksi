<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Support\Backup\BackupDatabaseConfiguration;
use App\Support\Backup\BackupProcess;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use RuntimeException;
use Throwable;

final class RestoreBackupRehearsal extends Command
{
    protected $signature = 'backup:restore-rehearsal
        {path? : Backup directory; defaults to newest backup}
        {--verify-only : Verify without importing}
        {--database= : Existing isolated rehearsal database}
        {--host=127.0.0.1 : Rehearsal database host}
        {--port=3306 : Rehearsal database port}
        {--username= : Rehearsal database username}
        {--password-env=BACKUP_REHEARSAL_DB_PASSWORD : Environment variable containing rehearsal password}';

    protected $description =
        'Verify a backup and optionally import it into an isolated database';

    public function handle(): int
    {
        $temporarySql = null;

        try {
            $directory = $this->resolveDirectory();

            $verifyStatus = $this->call(
                'backup:verify',
                ['path' => $directory],
            );

            if ($verifyStatus !== self::SUCCESS) {
                throw new RuntimeException(
                    'Backup verification failed.',
                );
            }

            if ((bool) $this->option('verify-only')) {
                $this->components->info(
                    'Verification-only restore rehearsal passed.',
                );

                return self::SUCCESS;
            }

            $targetDatabase = trim(
                (string) $this->option('database'),
            );

            $username = trim(
                (string) $this->option('username'),
            );

            if ($targetDatabase === '' || $username === '') {
                throw new RuntimeException(
                    'Use --verify-only or provide both '.
                    '--database and --username.',
                );
            }

            $production =
                BackupDatabaseConfiguration::production();

            if (
                strcasecmp(
                    $targetDatabase,
                    $production->database,
                ) === 0
            ) {
                throw new RuntimeException(
                    'Production database is forbidden as a '.
                    'restore-rehearsal target.',
                );
            }

            $passwordEnvironment = trim(
                (string) $this->option('password-env'),
            );

            if ($passwordEnvironment === '') {
                throw new RuntimeException(
                    'Password environment variable name is empty.',
                );
            }

            $password = getenv($passwordEnvironment);

            if (! is_string($password)) {
                throw new RuntimeException(
                    "Environment variable {$passwordEnvironment} ".
                    'is not defined.',
                );
            }

            $archive = $directory.'/database.sql.gz';

            if (! File::exists($archive)) {
                throw new RuntimeException(
                    'database.sql.gz is missing.',
                );
            }

            $temporarySql =
                storage_path(
                    'framework/'.
                    'restore-rehearsal-'.
                    bin2hex(random_bytes(8)).
                    '.sql',
                );

            BackupProcess::run(
                command: [
                    (string) config(
                        'backup.gzip_binary',
                        '/usr/bin/gzip',
                    ),
                    '--decompress',
                    '--stdout',
                    $archive,
                ],
                stdoutPath: $temporarySql,
            );

            if (
                ! File::exists($temporarySql) ||
                File::size($temporarySql) < 1
            ) {
                throw new RuntimeException(
                    'Decompressed rehearsal SQL is empty.',
                );
            }

            $host = trim(
                (string) $this->option('host'),
            );

            if (in_array(strtolower($host), ['localhost', '::1'], true)) {
                $host = '127.0.0.1';
            }

            BackupProcess::run(
                command: [
                    (string) config(
                        'backup.mysql_binary',
                        '/usr/bin/mysql',
                    ),
                    '--host='.$host,
                    '--port='.(string) $this->option('port'),
                    '--user='.$username,
                    '--protocol=TCP',
                    '--database='.$targetDatabase,
                ],
                stdinPath: $temporarySql,
                environment: [
                    'MYSQL_PWD' => $password,
                ],
            );

            $this->components->info(
                "Database import rehearsal passed: {$targetDatabase}",
            );

            return self::SUCCESS;
        } catch (Throwable $throwable) {
            $this->components->error($throwable->getMessage());

            return self::FAILURE;
        } finally {
            if (is_string($temporarySql)) {
                File::delete($temporarySql);
            }
        }
    }

    private function resolveDirectory(): string
    {
        $path = $this->argument('path');

        if (is_string($path) && trim($path) !== '') {
            $directory = rtrim(
                $path,
                DIRECTORY_SEPARATOR,
            );

            if (! File::isDirectory($directory)) {
                throw new RuntimeException(
                    "Backup directory does not exist: {$directory}",
                );
            }

            return $directory;
        }

        $root = rtrim(
            (string) config('backup.root'),
            DIRECTORY_SEPARATOR,
        );

        if (! File::isDirectory($root)) {
            throw new RuntimeException(
                'Backup root does not exist.',
            );
        }

        $directory = collect(File::directories($root))
            ->filter(
                static fn (string $candidate): bool =>
                    preg_match(
                        '/\/\d{8}-\d{6}$/',
                        $candidate,
                    ) === 1,
            )
            ->sortDesc()
            ->first();

        if (! is_string($directory)) {
            throw new RuntimeException(
                'No published backup was found.',
            );
        }

        return $directory;
    }
}
