<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Brand\Models\Brand;
use App\Domains\Domain\Enums\DomainType;
use App\Domains\Domain\Models\BrandDomain;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BrandDomain>
 */
class BrandDomainFactory extends Factory
{
    protected $model = BrandDomain::class;

    public function definition(): array
    {
        return [
            'brand_id' => Brand::factory(),
            'host' => fake()->unique()->domainName(),
            'type' => DomainType::Frontend,
            'is_primary' => false,
            'is_active' => true,
            'force_https' => true,
            'sort_order' => 0,
            'settings' => [],
        ];
    }

    public function primary(): static
    {
        return $this->state(fn (): array => [
            'is_primary' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => [
            'is_active' => false,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (): array => [
            'type' => DomainType::Admin,
        ]);
    }

    public function preview(): static
    {
        return $this->state(fn (): array => [
            'type' => DomainType::Preview,
        ]);
    }
}
