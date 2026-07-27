<?php

namespace Database\Factories;

use App\Domains\Brand\Models\Brand;
use App\Domains\Rtp\Models\BrandSlot;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BrandSlotFactory extends Factory
{
    protected $model = BrandSlot::class;
    public function definition(): array
    {
        $game = fake()->unique()->words(3, true);
        return ['brand_id'=>Brand::factory(),'provider_name'=>fake()->company(),'game_name'=>$game,'slug'=>Str::slug($game).'-'.fake()->unique()->numerify('###'),'image_url'=>null,'is_active'=>true,'is_published'=>false,'sort_order'=>0,'notes'=>null];
    }
    public function published(): static { return $this->state(fn()=>['is_active'=>true,'is_published'=>true]); }
}
