<?php

namespace Database\Factories;

use App\Domains\Brand\Models\Brand;
use App\Domains\Rtp\Models\BrandSlot;
use App\Domains\Rtp\Models\RtpSnapshot;
use Illuminate\Database\Eloquent\Factories\Factory;

class RtpSnapshotFactory extends Factory
{
    protected $model = RtpSnapshot::class;
    public function definition(): array
    {
        return ['brand_id'=>Brand::factory(),'brand_slot_id'=>fn(array $attributes)=>BrandSlot::factory()->create(['brand_id'=>$attributes['brand_id']])->id,'rtp_value'=>fake()->randomFloat(2, 0, 100),'captured_at'=>now(),'source_label'=>'manual','created_at'=>now()];
    }
}
