<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Support\Backup\BackupProcess;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use RuntimeException;
use Throwable;

final class VerifyProductionBackup extends Command
{
    protected $signature = 'backup:verify
        {path? : Backup directory; defaults to newest backup}';

    protected $description =
        'Verify production backup checksums and archive integrity';

    public function handle(): int
    {
        try {
            $directory = $this->resolveDirectory();
            $manifest = $this->readManifest($directory);

            $this->verifyArtifacts($directory, $manifest);

            $this->components->info(
                "Backup verification passed: {$directory}",
            );

            return self::SUCCESS;
        } catch (Throwable $throwable) {
            $this->components->error($throwable->getMessage());

            return self::FAILURE;
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

    /**
     * @return array<string, mixed>
     */
    private function readManifest(string $directory): array
    {
        $path = $directory.'/manifest.json';

        if (! File::exists($path)) {
            throw new RuntimeException(
                'manifest.json is missing.',
            );
        }

        $manifest = json_decode(
            File::get($path),
            true,
            flags: JSON_THROW_ON_ERROR,
        );

        if (
            ! is_array($manifest) ||
            ! isset($manifest['artifacts']) ||
            ! is_array($manifest['artifacts'])
        ) {
            throw new RuntimeException(
                'Backup manifest is invalid.',
            );
        }

        return $manifest;
    }

    /**
     * @param array<string, mixed> $manifest
     */
    private function verifyArtifacts(
        string $directory,
        array $manifest,
    ): void {
        foreach ($manifest['artifacts'] as $artifact) {
            if (
                ! is_array($artifact) ||
                ! isset(
                    $artifact['name'],
                    $artifact['size_bytes'],
                    $artifact['sha256'],
                )
            ) {
                throw new RuntimeException(
                    'Manifest artifact entry is invalid.',
                );
            }

            $originalName = (string) $artifact['name'];
            $name = basename($originalName);

            if ($name !== $originalName) {
                throw new RuntimeException(
                    'Manifest contains an unsafe artifact path.',
                );
            }

            $path = $directory.'/'.$name;

            if (! File::exists($path)) {
                throw new RuntimeException(
                    "Artifact is missing: {$name}",
                );
            }

            if (
                File::size($path) !==
                (int) $artifact['size_bytes']
            ) {
                throw new RuntimeException(
                    "Artifact size mismatch: {$name}",
                );
            }

            $actualChecksum = hash_file('sha256', $path);

            if (
                ! is_string($actualChecksum) ||
                ! hash_equals(
                    (string) $artifact['sha256'],
                    $actualChecksum,
                )
            ) {
                throw new RuntimeException(
                    "Artifact checksum mismatch: {$name}",
                );
            }

            if ($name === 'database.sql.gz') {
                BackupProcess::run(
                    command: [
                        (string) config(
                            'backup.gzip_binary',
                            '/usr/bin/gzip',
                        ),
                        '--test',
                        $path,
                    ],
                );
            }

            if ($name === 'storage-public.tar.gz') {
                BackupProcess::run(
                    command: [
                        (string) config(
                            'backup.tar_binary',
                            '/usr/bin/tar',
                        ),
                        '--list',
                        '--gzip',
                        '--file='.$path,
                    ],
                );
            }
        }
    }
}
