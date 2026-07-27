<?php

namespace Database\Seeders;

use App\Domains\Market\Models\Market;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MarketSeeder extends Seeder
{
    public function run(): void
    {
        $markets = [
            [
                'code' => 'SGP',
                'name' => 'Singapore',
                'timezone' => 'Asia/Singapore',
            ],
            [
                'code' => 'HK',
                'name' => 'Hong Kong',
                'timezone' => 'Asia/Hong_Kong',
            ],
            [
                'code' => 'SDY',
                'name' => 'Sydney',
                'timezone' => 'Australia/Sydney',
            ],
            [
                'code' => 'JP',
                'name' => 'Japan',
                'timezone' => 'Asia/Tokyo',
            ],
            [
                'code' => 'TW',
                'name' => 'Taiwan',
                'timezone' => 'Asia/Taipei',
            ],
            [
                'code' => 'CAM',
                'name' => 'Cambodia',
                'timezone' => 'Asia/Phnom_Penh',
            ],
        ];

        foreach ($markets as $index => $market) {
            Market::updateOrCreate(
                [
                    'code' => $market['code'],
                ],
                [
                    'name' => $market['name'],
                    'slug' => Str::slug($market['name']),
                    'timezone' => $market['timezone'],
                    'is_active' => true,
                    'sort_order' => $index + 1,
                    'notes' => null,
                ],
            );
        }
    }
}
