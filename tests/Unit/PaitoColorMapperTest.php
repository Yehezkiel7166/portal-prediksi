<?php

namespace Tests\Unit;

use App\Domains\Paito\Support\PaitoColorMapper;
use PHPUnit\Framework\TestCase;

final class PaitoColorMapperTest extends TestCase
{
    public function test_mapping_is_deterministic_and_ignores_non_digits(): void
    {
        $mapper = new PaitoColorMapper();
        $result = $mapper->map('12-90');

        self::assertSame(['1', '2', '9', '0'], array_column($result, 'digit'));
        self::assertSame($result, $mapper->map('12-90'));
    }
}
