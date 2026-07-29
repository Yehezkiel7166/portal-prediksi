<?php

declare(strict_types=1);

namespace App\Support\Backup;

use RuntimeException;

final class BackupProcess
{
    /**
     * @param array<int, string> $command
     * @param array<string, string> $environment
     */
    public static function run(
        array $command,
        ?string $stdinPath = null,
        ?string $stdoutPath = null,
        array $environment = [],
    ): string {
        if ($command === []) {
            throw new RuntimeException('Process command may not be empty.');
        }

        $descriptors = [
            0 => [
                'file',
                $stdinPath ?? '/dev/null',
                'r',
            ],
            1 => $stdoutPath === null
                ? ['pipe', 'w']
                : ['file', $stdoutPath, 'w'],
            2 => ['pipe', 'w'],
        ];

        $processEnvironment = self::environment($environment);

        $process = proc_open(
            $command,
            $descriptors,
            $pipes,
            null,
            $processEnvironment,
            ['bypass_shell' => true],
        );

        unset($processEnvironment['MYSQL_PWD']);

        if (! is_resource($process)) {
            throw new RuntimeException(
                'Unable to start operating-system process.',
            );
        }

        $stdout = '';

        if ($stdoutPath === null && isset($pipes[1])) {
            $stdout = stream_get_contents($pipes[1]) ?: '';
            fclose($pipes[1]);
        }

        $stderr = stream_get_contents($pipes[2]) ?: '';
        fclose($pipes[2]);

        $exitCode = proc_close($process);

        if ($exitCode !== 0) {
            throw new RuntimeException(
                sprintf(
                    'Process failed with exit code %d: %s',
                    $exitCode,
                    self::sanitize($stderr),
                ),
            );
        }

        return $stdout;
    }

    /**
     * @param array<string, string> $additional
     *
     * @return array<string, string>
     */
    private static function environment(array $additional): array
    {
        $environment = [];

        foreach ($_SERVER as $key => $value) {
            if (
                is_string($key) &&
                (is_string($value) || is_numeric($value))
            ) {
                $environment[$key] = (string) $value;
            }
        }

        $environment['PATH'] = getenv('PATH')
            ?: '/usr/local/bin:/usr/bin:/bin';

        $environment['HOME'] = getenv('HOME') ?: '';

        foreach ($additional as $key => $value) {
            $environment[$key] = $value;
        }

        return $environment;
    }

    private static function sanitize(string $message): string
    {
        $sanitized = preg_replace(
            [
                '/password=[^\s]+/i',
                '/using password:\s*yes/i',
            ],
            [
                'password=[REDACTED]',
                'using password: [REDACTED]',
            ],
            trim($message),
        );

        return $sanitized ?: 'No process error output was returned.';
    }
}
