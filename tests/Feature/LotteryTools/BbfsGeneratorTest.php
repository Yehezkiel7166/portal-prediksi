<?php

namespace Tests\Feature\LotteryTools;

use App\Domains\Bbfs\Support\BbfsGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

final class BbfsGeneratorTest extends TestCase
{
    use RefreshDatabase;

    public function test_generator_is_deterministic_and_removes_duplicate_input_digits(): void
    {
        $generator = app(BbfsGenerator::class);

        $first = $generator->generate('1 2 2 3', 2);
        $second = $generator->generate('1223', 2);

        $this->assertSame('123', $first['digits']);
        $this->assertSame(['12', '13', '21', '23', '31', '32'], $first['combinations']);
        $this->assertSame($first, $second);
    }

    public function test_generator_rejects_output_longer_than_unique_digit_count(): void
    {
        $this->expectException(InvalidArgumentException::class);

        app(BbfsGenerator::class)->generate('12', 3);
    }

    public function test_public_form_generates_safe_output_without_database_persistence(): void
    {
        $this->get(route('tools.bbfs.create'))
            ->assertOk()
            ->assertSee('BBFS Generator')
            ->assertSee('canonical');

        $this->post(route('tools.bbfs.store'), [
            'digits' => '123',
            'length' => 2,
        ])
            ->assertOk()
            ->assertSee('Total 6 kombinasi')
            ->assertSee('12 · 13 · 21 · 23 · 31 · 32');
    }

    public function test_public_form_rejects_invalid_input(): void
    {
        $this->from(route('tools.bbfs.create'))
            ->post(route('tools.bbfs.store'), [
                'digits' => '12<script>',
                'length' => 2,
            ])
            ->assertSessionHasErrors('digits');
    }
}
