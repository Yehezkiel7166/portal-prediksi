<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Support\Backup\BackupDatabaseConfiguration;
use App\Support\Backup\BackupProcess;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use RuntimeException;
use Throwable;

final class CreateProductionBackup extends Command
{
    protected $signature = 'backup:create
        {--no-retention : Skip retention cleanup}
        {--quiet-result : Suppress artifact listing}';

    protected $description =
        'Create and verify an atomic production backup';

    public function handle(): int
    {
        $startedAt = now();
        $identifier = $startedAt->format('Ymd-His');

        $root = rtrim(
            (string) config('backup.root'),
            DIRECTORY_SEPARATOR,
        );

        $workRoot = rtrim(
            (string) config('backup.work_root'),
            DIRECTORY_SEPARATOR,
        );

        $workingDirectory =
            $workRoot.DIRECTORY_SEPARATOR.$identifier;

        $publishedDirectory =
            $root.DIRECTORY_SEPARATOR.$identifier;

        try {
            $this->assertDestinationSafety($root, $workRoot);

            File::ensureDirectoryExists($root, 0700, true);
            File::ensureDirectoryExists($workRoot, 0700, true);

            @chmod($root, 0700);
            @chmod($workRoot, 0700);

            if (
                File::exists($workingDirectory) ||
                File::exists($publishedDirectory)
            ) {
                throw new RuntimeException(
                    "Backup identifier already exists: {$identifier}",
                );
            }

            File::makeDirectory(
                $workingDirectory,
                0700,
                true,
                true,
            );

            @chmod($workingDirectory, 0700);

            $database =
                BackupDatabaseConfiguration::production();

            $databaseArchive =
                $workingDirectory.'/database.sql.gz';

            $this->components->task(
                'Creating database backup',
                function () use (
                    $database,
                    $databaseArchive,
                ): bool {
                    $this->createDatabaseArchive(
                        $database,
                        $databaseArchive,
                    );

                    return true;
                },
            );

            $artifacts = ['database.sql.gz'];

            if ((bool) config('backup.include_public_storage', true)) {
                $storageArchive =
                    $workingDirectory.'/storage-public.tar.gz';

                $included = false;

                $this->components->task(
                    'Creating public storage backup',
                    function () use (
                        $storageArchive,
                        &$included,
                    ): bool {
                        $included = $this->createStorageArchive(
                            $storageArchive,
                        );

                        return true;
                    },
                );

                if ($included) {
                    $artifacts[] = 'storage-public.tar.gz';
                }
            }

            $checksums = $this->createChecksums(
                $workingDirectory,
                $artifacts,
            );

            $manifest = [
                'schema_version' => 1,
                'application' => (string) config(
                    'app.name',
                    'Portal Prediksi',
                ),
                'created_at' => $startedAt->toIso8601String(),
                'completed_at' => now()->toIso8601String(),
                'git_commit' => $this->currentCommit(),
                'database_connection' => (string) config('database.default'),
                'database_name' => $database->database,
                'public_storage_included' => in_array(
                    'storage-public.tar.gz',
                    $artifacts,
                    true,
                ),
                'artifacts' => array_map(
                    function (string $artifact) use (
                        $workingDirectory,
                        $checksums,
                    ): array {
                        return [
                            'name' => $artifact,
                            'size_bytes' => File::size(
                                $workingDirectory.'/'.$artifact,
                            ),
                            'sha256' => $checksums[$artifact],
                        ];
                    },
                    $artifacts,
                ),
            ];

            File::put(
                $workingDirectory.'/manifest.json',
                json_encode(
                    $manifest,
                    JSON_PRETTY_PRINT |
                    JSON_UNESCAPED_SLASHES |
                    JSON_THROW_ON_ERROR,
                ).PHP_EOL,
                true,
            );

            $this->verifyWorkingDirectory(
                $workingDirectory,
                $artifacts,
                $checksums,
            );

            if (! rename($workingDirectory, $publishedDirectory)) {
                throw new RuntimeException(
                    'Unable to atomically publish backup.',
                );
            }

            @chmod($publishedDirectory, 0700);

            if (! $this->option('no-retention')) {
                $this->applyRetention(
                    $root,
                    $publishedDirectory,
                );
            }

            $this->components->info(
                "Backup published: {$publishedDirectory}",
            );

            if (! $this->option('quiet-result')) {
                foreach ($artifacts as $artifact) {
                    $this->line(
                        sprintf(
                            '%s  %d bytes',
                            $artifact,
                            File::size(
                                $publishedDirectory.'/'.$artifact,
                            ),
                        ),
                    );
                }
            }

            return self::SUCCESS;
        } catch (Throwable $throwable) {
            if (File::isDirectory($workingDirectory)) {
                File::deleteDirectory($workingDirectory);
            }

            $this->components->error($throwable->getMessage());

            return self::FAILURE;
        }
    }

    private function createDatabaseArchive(
        BackupDatabaseConfiguration $database,
        string $destination,
    ): void {
        $mysqldump = (string) config(
            'backup.mysqldump_binary',
            '/usr/bin/mysqldump',
        );

        $gzip = (string) config(
            'backup.gzip_binary',
            '/usr/bin/gzip',
        );

        $temporarySql = $destination.'.tmp.sql';
        $temporaryGzip = $temporarySql.'.gz';

        try {
            $command = array_merge(
                [$mysqldump],
                $database->clientArguments(),
                [
                    '--single-transaction',
                    '--quick',
                    '--routines',
                    '--triggers',
                    '--events',
                    '--skip-comments',
                    '--hex-blob',
                    '--default-character-set=utf8mb4',
                    $database->database,
                ],
            );

            BackupProcess::run(
                command: $command,
                stdoutPath: $temporarySql,
                environment: [
                    'MYSQL_PWD' => $database->password,
                ],
            );

            if (
                ! File::exists($temporarySql) ||
                File::size($temporarySql) < 1
            ) {
                throw new RuntimeException(
                    'mysqldump produced an empty SQL file.',
                );
            }

            BackupProcess::run(
                command: [
                    $gzip,
                    '--force',
                    '--best',
                    $temporarySql,
                ],
            );

            if (
                ! File::exists($temporaryGzip) ||
                File::size($temporaryGzip) < 1
            ) {
                throw new RuntimeException(
                    'gzip did not create a database archive.',
                );
            }

            if (! rename($temporaryGzip, $destination)) {
                throw new RuntimeException(
                    'Unable to publish database archive.',
                );
            }

            BackupProcess::run(
                command: [
                    $gzip,
                    '--test',
                    $destination,
                ],
            );
        } finally {
            File::delete([
                $temporarySql,
                $temporaryGzip,
            ]);
        }
    }

    private function createStorageArchive(
        string $destination,
    ): bool {
        $source = (string) config(
            'backup.public_storage_path',
            storage_path('app/public'),
        );

        if (! File::isDirectory($source)) {
            return false;
        }

        $tar = (string) config(
            'backup.tar_binary',
            '/usr/bin/tar',
        );

        BackupProcess::run(
            command: [
                $tar,
                '--create',
                '--gzip',
                '--file='.$destination,
                '--directory='.dirname($source),
                basename($source),
            ],
        );

        if (
            ! File::exists($destination) ||
            File::size($destination) < 1
        ) {
            throw new RuntimeException(
                'Public storage archive is empty.',
            );
        }

        BackupProcess::run(
            command: [
                $tar,
                '--list',
                '--gzip',
                '--file='.$destination,
            ],
        );

        return true;
    }

    /**
     * @param array<int, string> $artifacts
     *
     * @return array<string, string>
     */
    private function createChecksums(
        string $directory,
        array $artifacts,
    ): array {
        $checksums = [];
        $contents = '';

        foreach ($artifacts as $artifact) {
            $path = $directory.'/'.$artifact;
            $checksum = hash_file('sha256', $path);

            if (! is_string($checksum) || strlen($checksum) !== 64) {
                throw new RuntimeException(
                    "Unable to calculate checksum: {$artifact}",
                );
            }

            $checksums[$artifact] = $checksum;
            $contents .= "{$checksum}  {$artifact}".PHP_EOL;
        }

        File::put(
            $directory.'/SHA256SUMS',
            $contents,
            true,
        );

        return $checksums;
    }

    /**
     * @param array<int, string> $artifacts
     * @param array<string, string> $checksums
     */
    private function verifyWorkingDirectory(
        string $directory,
        array $artifacts,
        array $checksums,
    ): void {
        foreach ($artifacts as $artifact) {
            $path = $directory.'/'.$artifact;

            if (! File::exists($path) || File::size($path) < 1) {
                throw new RuntimeException(
                    "Missing or empty artifact: {$artifact}",
                );
            }

            $actual = hash_file('sha256', $path);

            if (
                ! is_string($actual) ||
                ! hash_equals($checksums[$artifact], $actual)
            ) {
                throw new RuntimeException(
                    "Checksum verification failed: {$artifact}",
                );
            }
        }

        foreach (['manifest.json', 'SHA256SUMS'] as $metadata) {
            $path = $directory.'/'.$metadata;

            if (! File::exists($path) || File::size($path) < 1) {
                throw new RuntimeException(
                    "Missing backup metadata: {$metadata}",
                );
            }
        }
    }

    private function applyRetention(
        string $root,
        string $newestBackup,
    ): void {
        $minimumBackups = max(
            1,
            (int) config('backup.minimum_backups', 3),
        );

        $retentionDays = max(
            1,
            (int) config('backup.retention_days', 14),
        );

        $directories = collect(File::directories($root))
            ->filter(
                static fn (string $directory): bool =>
                    preg_match(
                        '/\/\d{8}-\d{6}$/',
                        $directory,
                    ) === 1,
            )
            ->sortDesc()
            ->values();

        if (! $directories->contains($newestBackup)) {
            throw new RuntimeException(
                'Retention refused: newest backup not found.',
            );
        }

        $cutoff = now()
            ->subDays($retentionDays)
            ->getTimestamp();

        $directories
            ->slice($minimumBackups)
            ->each(
                static function (
                    string $directory,
                ) use ($cutoff): void {
                    if (File::lastModified($directory) < $cutoff) {
                        File::deleteDirectory($directory);
                    }
                },
            );
    }

    private function assertDestinationSafety(
        string $root,
        string $workRoot,
    ): void {
        $publicPath = rtrim(
            realpath(public_path()) ?: public_path(),
            DIRECTORY_SEPARATOR,
        );

        foreach ([$root, $workRoot] as $candidate) {
            $normalized = rtrim(
                $candidate,
                DIRECTORY_SEPARATOR,
            );

            if (
                $normalized === '' ||
                $normalized === DIRECTORY_SEPARATOR
            ) {
                throw new RuntimeException(
                    'Unsafe backup destination.',
                );
            }

            if (
                $normalized === $publicPath ||
                str_starts_with(
                    $normalized,
                    $publicPath.DIRECTORY_SEPARATOR,
                )
            ) {
                throw new RuntimeException(
                    'Backup destination may not be inside public.',
                );
            }
        }

        if ($root === $workRoot) {
            throw new RuntimeException(
                'Published and working backup paths must differ.',
            );
        }
    }

    private function currentCommit(): ?string
    {
        try {
            $commit = trim(
                BackupProcess::run(
                    command: [
                        '/usr/bin/git',
                        'rev-parse',
                        'HEAD',
                    ],
                ),
            );

            return preg_match('/^[a-f0-9]{40}$/', $commit) === 1
                ? $commit
                : null;
        } catch (Throwable) {
            return null;
        }
    }
}
