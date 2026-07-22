<?php

namespace Database\Factories;

use App\Domains\Brand\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

class BrandFactory extends Factory
{
    protected $model = Brand::class;

    public function definition(): array
    {
        $slug = fake()->unique()->slug();

        return [
            'code' => strtoupper(fake()->unique()->lexify('BRAND???')),
            'name' => fake()->company(),
            'slug' => $slug,
            'domain' => $slug . '.test',
            'is_active' => true,
            'sort_order' => 0,
            'settings' => [],
        ];
    }
}
