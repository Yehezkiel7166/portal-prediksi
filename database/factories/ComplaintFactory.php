<?php

namespace Database\Factories;

use App\Domains\Brand\Models\Brand;
use App\Domains\Complaint\Models\Complaint;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ComplaintFactory extends Factory
{
    protected $model = Complaint::class;

    public function definition(): array
    {
        return [
            'brand_id' => Brand::factory(),
            'reference_code' => 'KLG-'.now()->format('ymd').'-'.Str::upper(Str::random(8)),
            'name' => fake()->name(),
            'contact' => fake()->email(),
            'subject' => fake()->sentence(5),
            'message' => fake()->paragraphs(2, true),
            'status' => Complaint::STATUS_OPEN,
            'reviewed_at' => null,
            'resolved_at' => null,
            'handled_by' => null,
            'admin_notes' => null,
            'admin_response' => null,
            'responded_at' => null,
            'source_ip' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
        ];
    }
}
