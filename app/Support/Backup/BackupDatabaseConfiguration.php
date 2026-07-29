<?php

declare(strict_types=1);

namespace App\Support\Backup;

use RuntimeException;

final readonly class BackupDatabaseConfiguration
{
    public function __construct(
        public string $host,
        public string $port,
        public string $database,
        public string $username,
        public string $password,
    ) {
    }

    public static function production(): self
    {
        $connection = (string) config('database.default');

        $configuration = config(
            "database.connections.{$connection}",
        );

        if ($connection !== 'mysql' || ! is_array($configuration)) {
            throw new RuntimeException(
                'Canonical production connection must be MySQL.',
            );
        }

        foreach (
            ['host', 'port', 'database', 'username', 'password']
            as $required
        ) {
            if (! array_key_exists($required, $configuration)) {
                throw new RuntimeException(
                    "Missing database configuration: {$required}.",
                );
            }
        }

        $host = (string) $configuration['host'];

        if (in_array(strtolower($host), ['localhost', '::1'], true)) {
            $host = '127.0.0.1';
        }

        return new self(
            host: $host,
            port: (string) $configuration['port'],
            database: (string) $configuration['database'],
            username: (string) $configuration['username'],
            password: (string) $configuration['password'],
        );
    }

    /**
     * @return array<int, string>
     */
    public function clientArguments(): array
    {
        return [
            '--host='.$this->host,
            '--port='.$this->port,
            '--user='.$this->username,
            '--protocol=TCP',
        ];
    }
}
