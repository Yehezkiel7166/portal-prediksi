<?php

namespace Database\Factories;

use App\Domains\Shio\Models\ShioNumber;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShioNumberFactory extends Factory
{
    protected $model = ShioNumber::class;

    public function definition(): array
    {
        return [
            'name' => 'KAMBING',
            'numbers' => [
                '12',
                '24',
                '36',
                '48',
                '60',
                '72',
                '84',
                '96',
            ],
            'sort_order' => 1,
        ];
    }
}
