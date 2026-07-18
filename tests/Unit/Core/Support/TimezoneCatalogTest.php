<?php

namespace Tests\Unit\Core\Support;

use App\Core\Support\TimezoneCatalog;
use DateTimeZone;
use PHPUnit\Framework\TestCase;

class TimezoneCatalogTest extends TestCase
{
    protected function tearDown(): void
    {
        TimezoneCatalog::clearCache();

        parent::tearDown();
    }

    public function test_options_contains_every_php_iana_timezone(): void
    {
        $expected = DateTimeZone::listIdentifiers(
            DateTimeZone::ALL
        );

        $options = TimezoneCatalog::options();

        $this->assertCount(
            count($expected),
            $options,
        );

        $this->assertSame(
            $expected,
            array_keys($options),
        );

        $this->assertSame(
            $expected,
            array_values($options),
        );
    }

    public function test_contains_recognizes_valid_and_invalid_timezone(): void
    {
        $this->assertTrue(
            TimezoneCatalog::contains('Asia/Jakarta')
        );

        $this->assertTrue(
            TimezoneCatalog::contains('America/New_York')
        );

        $this->assertFalse(
            TimezoneCatalog::contains('Invalid/Timezone')
        );
    }

    public function test_options_are_reusable_without_rebuilding_the_catalog(): void
    {
        $first = TimezoneCatalog::options();
        $second = TimezoneCatalog::options();

        $this->assertSame($first, $second);
    }
}
