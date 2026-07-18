<?php

namespace Tests\Concerns;

use RuntimeException;

trait EnsuresSafeTestDatabase
{
    protected function ensureSafeTestDatabase(): void
    {
        if (! app()->environment('testing')) {
            throw new RuntimeException(
                'Test dihentikan karena APP_ENV bukan testing.'
            );
        }

        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
        $database = config("database.connections.{$connection}.database");

        if ($connection !== 'sqlite') {
            throw new RuntimeException(
                sprintf(
                    'Test dihentikan karena koneksi aktif adalah [%s], bukan [sqlite].',
                    (string) $connection,
                )
            );
        }

        if ($driver !== 'sqlite') {
            throw new RuntimeException(
                sprintf(
                    'Test dihentikan karena driver aktif adalah [%s], bukan [sqlite].',
                    (string) $driver,
                )
            );
        }

        if ($database !== ':memory:') {
            throw new RuntimeException(
                sprintf(
                    'Test dihentikan karena database aktif adalah [%s], bukan [:memory:].',
                    (string) $database,
                )
            );
        }
    }
}
