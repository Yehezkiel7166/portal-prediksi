<?php

namespace Database\Factories;

use App\Domains\Shio\Models\ShioPeriod;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShioPeriodFactory extends Factory
{
    protected $model = ShioPeriod::class;

    public function definition(): array
    {
        return [
            'year' => 2026,
            'title' => 'Tabel Shio 2026',
            'start_date' => '2026-02-17',
            'end_date' => '2027-02-05',
            'status' => 'draft',
        ];
    }
}
