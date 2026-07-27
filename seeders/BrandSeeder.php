<?php

namespace Database\Seeders;

use App\Domains\Brand\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        Brand::updateOrCreate(
            ['code' => 'DEFAULT'],
            [
                'name' => 'Default Brand',
                'slug' => 'default',
                'domain' => 'localhost',
                'is_active' => true,
                'sort_order' => 1,
                'settings' => [],
            ]
        );
    }
}
