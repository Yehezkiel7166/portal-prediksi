<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Concerns\EnsuresSafeTestDatabase;

abstract class TestCase extends BaseTestCase
{
    use EnsuresSafeTestDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->ensureSafeTestDatabase();
    }
}
