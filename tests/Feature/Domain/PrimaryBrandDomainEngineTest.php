<?php

declare(strict_types=1);

namespace Tests\Feature\Domain;

use App\Domains\Brand\Models\Brand;
use App\Domains\Domain\Actions\RemovePrimaryBrandDomain;
use App\Domains\Domain\Actions\ResolvePrimaryBrandDomain;
use App\Domains\Domain\Actions\SetPrimaryBrandDomain;
use App\Domains\Domain\Actions\UpdateBrandDomainStatus;
use App\Domains\Domain\Enums\DomainType;
use App\Domains\Domain\Exceptions\InvalidPrimaryBrandDomain;
use App\Domains\Domain\Models\BrandDomain;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrimaryBrandDomainEngineTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sets_domain_as_primary(): void
    {
        $domain = BrandDomain::factory()->create([
            'is_active' => true,
            'is_primary' => false,
            'type' => DomainType::Frontend,
        ]);

        $result = app(SetPrimaryBrandDomain::class)->execute($domain);

        $this->assertTrue($result->is_primary);
        $this->assertDatabaseHas('brand_domains', [
            'id' => $domain->getKey(),
            'is_primary' => true,
        ]);
    }

    public function test_it_removes_previous_primary_for_same_brand_and_type(): void
    {
        $brand = Brand::factory()->create();

        $previous = BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'previous.example.test',
                'type' => DomainType::Frontend,
                'is_active' => true,
                'is_primary' => true,
            ]);

        $replacement = BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'replacement.example.test',
                'type' => DomainType::Frontend,
                'is_active' => true,
                'is_primary' => false,
            ]);

        app(SetPrimaryBrandDomain::class)->execute($replacement);

        $this->assertFalse($previous->refresh()->is_primary);
        $this->assertTrue($replacement->refresh()->is_primary);
    }

    public function test_it_does_not_change_primary_for_another_brand(): void
    {
        $firstBrand = Brand::factory()->create();
        $secondBrand = Brand::factory()->create();

        $firstPrimary = BrandDomain::factory()
            ->for($firstBrand)
            ->create([
                'host' => 'first.example.test',
                'type' => DomainType::Frontend,
                'is_active' => true,
                'is_primary' => true,
            ]);

        $secondDomain = BrandDomain::factory()
            ->for($secondBrand)
            ->create([
                'host' => 'second.example.test',
                'type' => DomainType::Frontend,
                'is_active' => true,
                'is_primary' => false,
            ]);

        app(SetPrimaryBrandDomain::class)->execute($secondDomain);

        $this->assertTrue($firstPrimary->refresh()->is_primary);
        $this->assertTrue($secondDomain->refresh()->is_primary);
    }

    public function test_it_does_not_change_primary_for_another_domain_type(): void
    {
        $brand = Brand::factory()->create();

        $frontend = BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'frontend.example.test',
                'type' => DomainType::Frontend,
                'is_active' => true,
                'is_primary' => true,
            ]);

        $admin = BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'admin.example.test',
                'type' => DomainType::Admin,
                'is_active' => true,
                'is_primary' => false,
            ]);

        app(SetPrimaryBrandDomain::class)->execute($admin);

        $this->assertTrue($frontend->refresh()->is_primary);
        $this->assertTrue($admin->refresh()->is_primary);
    }

    public function test_it_rejects_inactive_domain_as_primary(): void
    {
        $domain = BrandDomain::factory()->create([
            'is_active' => false,
            'is_primary' => false,
        ]);

        $this->expectException(InvalidPrimaryBrandDomain::class);
        $this->expectExceptionMessage(
            'Inactive domain cannot be selected as primary.'
        );

        app(SetPrimaryBrandDomain::class)->execute($domain);
    }

    public function test_it_rejects_unsaved_domain_as_primary(): void
    {
        $domain = BrandDomain::factory()->make([
            'is_active' => true,
            'is_primary' => false,
        ]);

        $this->expectException(InvalidPrimaryBrandDomain::class);
        $this->expectExceptionMessage(
            'Primary domain must already exist in the database.'
        );

        app(SetPrimaryBrandDomain::class)->execute($domain);
    }

    public function test_setting_current_primary_again_is_idempotent(): void
    {
        $domain = BrandDomain::factory()->create([
            'is_active' => true,
            'is_primary' => true,
        ]);

        $first = app(SetPrimaryBrandDomain::class)->execute($domain);
        $second = app(SetPrimaryBrandDomain::class)->execute($first);

        $this->assertTrue($second->is_primary);

        $this->assertSame(
            1,
            BrandDomain::query()
                ->where('brand_id', $domain->brand_id)
                ->where('type', $domain->type)
                ->where('is_primary', true)
                ->count()
        );
    }

    public function test_it_can_remove_primary_status(): void
    {
        $domain = BrandDomain::factory()->create([
            'is_active' => true,
            'is_primary' => true,
        ]);

        $result = app(RemovePrimaryBrandDomain::class)->execute($domain);

        $this->assertFalse($result->is_primary);
    }

    public function test_deactivating_domain_removes_primary_status(): void
    {
        $domain = BrandDomain::factory()->create([
            'is_active' => true,
            'is_primary' => true,
        ]);

        $result = app(UpdateBrandDomainStatus::class)->execute(
            $domain,
            false,
        );

        $this->assertFalse($result->is_active);
        $this->assertFalse($result->is_primary);
    }

    public function test_activating_domain_does_not_automatically_make_it_primary(): void
    {
        $domain = BrandDomain::factory()->create([
            'is_active' => false,
            'is_primary' => false,
        ]);

        $result = app(UpdateBrandDomainStatus::class)->execute(
            $domain,
            true,
        );

        $this->assertTrue($result->is_active);
        $this->assertFalse($result->is_primary);
    }

    public function test_it_resolves_active_primary_domain_by_brand_and_type(): void
    {
        $brand = Brand::factory()->create();

        BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'secondary.example.test',
                'type' => DomainType::Frontend,
                'is_active' => true,
                'is_primary' => false,
            ]);

        $primary = BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'primary.example.test',
                'type' => DomainType::Frontend,
                'is_active' => true,
                'is_primary' => true,
            ]);

        $resolved = app(ResolvePrimaryBrandDomain::class)->execute(
            $brand,
            DomainType::Frontend,
        );

        $this->assertNotNull($resolved);
        $this->assertTrue($primary->is($resolved));
    }

    public function test_resolver_does_not_return_inactive_primary_domain(): void
    {
        $brand = Brand::factory()->create();

        BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'inactive-primary.example.test',
                'type' => DomainType::Frontend,
                'is_active' => false,
                'is_primary' => true,
            ]);

        $resolved = app(ResolvePrimaryBrandDomain::class)->execute(
            $brand,
            DomainType::Frontend,
        );

        $this->assertNull($resolved);
    }

    public function test_only_one_primary_remains_after_multiple_switches(): void
    {
        $brand = Brand::factory()->create();

        $domains = collect([
            'one.example.test',
            'two.example.test',
            'three.example.test',
        ])->map(
            fn (string $host): BrandDomain => BrandDomain::factory()
                ->for($brand)
                ->create([
                    'host' => $host,
                    'type' => DomainType::Frontend,
                    'is_active' => true,
                    'is_primary' => false,
                ])
        );

        $action = app(SetPrimaryBrandDomain::class);

        foreach ($domains as $domain) {
            $action->execute($domain);
        }

        $this->assertSame(
            1,
            BrandDomain::query()
                ->where('brand_id', $brand->getKey())
                ->where('type', DomainType::Frontend)
                ->where('is_primary', true)
                ->count()
        );

        $this->assertTrue(
            $domains->last()->refresh()->is_primary
        );
    }
}
