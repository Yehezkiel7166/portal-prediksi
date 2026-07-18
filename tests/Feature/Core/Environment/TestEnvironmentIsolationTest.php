<?php

namespace Tests\Feature\Core\Environment;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TestEnvironmentIsolationTest extends TestCase
{
    public function test_phpunit_uses_isolated_sqlite_memory_database(): void
    {
        $connection = config('database.default');

        $this->assertTrue(app()->environment('testing'));
        $this->assertSame('sqlite', $connection);
        $this->assertSame(
            'sqlite',
            config("database.connections.{$connection}.driver"),
        );
        $this->assertSame(
            ':memory:',
            config("database.connections.{$connection}.database"),
        );
        $this->assertSame(
            'sqlite',
            DB::connection()->getDriverName(),
        );
        $this->assertSame(
            ':memory:',
            DB::connection()->getDatabaseName(),
        );
    }
}
