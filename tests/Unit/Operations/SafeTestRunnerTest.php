<?php

declare(strict_types=1);

namespace Tests\Unit\Operations;

use PHPUnit\Framework\TestCase;

final class SafeTestRunnerTest extends TestCase
{
    private string $runner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->runner = dirname(__DIR__, 3).'/bin/test-safe';

        self::assertFileExists($this->runner);
        self::assertIsReadable($this->runner);
    }

    public function test_runner_enforces_testing_environment(): void
    {
        $contents = $this->contents();

        self::assertStringContainsString(
            'export APP_ENV="testing"',
            $contents,
        );

        self::assertStringContainsString(
            'export APP_DEBUG="false"',
            $contents,
        );
    }

    public function test_runner_enforces_safe_in_memory_database(): void
    {
        $contents = $this->contents();

        self::assertStringContainsString(
            'export DB_CONNECTION="sqlite"',
            $contents,
        );

        self::assertStringContainsString(
            'export DB_DATABASE=":memory:"',
            $contents,
        );

        self::assertStringContainsString(
            'export DB_URL=""',
            $contents,
        );
    }

    public function test_runner_isolates_laravel_bootstrap_caches(): void
    {
        $contents = $this->contents();

        foreach ([
            'APP_CONFIG_CACHE',
            'APP_SERVICES_CACHE',
            'APP_PACKAGES_CACHE',
            'APP_EVENTS_CACHE',
            'APP_ROUTES_CACHE',
        ] as $environmentVariable) {
            self::assertStringContainsString(
                'export '.$environmentVariable.'=',
                $contents,
            );
        }
    }

    public function test_runner_uses_non_persistent_drivers(): void
    {
        $contents = $this->contents();

        self::assertStringContainsString(
            'export CACHE_STORE="array"',
            $contents,
        );

        self::assertStringContainsString(
            'export QUEUE_CONNECTION="sync"',
            $contents,
        );

        self::assertStringContainsString(
            'export SESSION_DRIVER="array"',
            $contents,
        );

        self::assertStringContainsString(
            'export MAIL_MAILER="array"',
            $contents,
        );
    }

    public function test_runner_does_not_clear_production_cache(): void
    {
        $contents = $this->contents();

        self::assertStringNotContainsString(
            'artisan config:clear',
            $contents,
        );

        self::assertStringNotContainsString(
            'artisan optimize:clear',
            $contents,
        );

        self::assertStringNotContainsString(
            'bootstrap/cache/config.php',
            $contents,
        );
    }

    private function contents(): string
    {
        $contents = file_get_contents($this->runner);

        self::assertIsString($contents);

        return $contents;
    }
}
