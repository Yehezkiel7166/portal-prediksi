<?php

declare(strict_types=1);

namespace Tests\Feature\Domain;

use App\Domains\Brand\Models\Brand;
use App\Domains\Domain\Actions\MigrateBrandDomain;
use App\Domains\Domain\Actions\PrepareBrandDomainMigration;
use App\Domains\Domain\Enums\DomainType;
use App\Domains\Domain\Exceptions\InvalidBrandDomainMigration;
use App\Domains\Domain\Models\BrandDomain;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DomainMigrationEngineTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_prepares_domain_migration_plan(): void
    {
        [$sourceBrand, $targetBrand] = $this->brands();

        $domain = $this->domain($sourceBrand, [
            'host' => 'frontend.example.com',
            'type' => DomainType::Frontend,
            'is_primary' => true,
        ]);

        $plan = app(PrepareBrandDomainMigration::class)->execute(
            domain: $domain,
            targetBrand: $targetBrand,
            makePrimary: true,
        );

        $this->assertSame($domain->id, $plan->domainId);
        $this->assertSame('frontend.example.com', $plan->host);
        $this->assertSame($sourceBrand->id, $plan->sourceBrandId);
        $this->assertSame($targetBrand->id, $plan->targetBrandId);
        $this->assertSame('frontend', $plan->type);
        $this->assertTrue($plan->wasPrimary);
        $this->assertTrue($plan->willBecomePrimary);
        $this->assertTrue($plan->brandWillChange);
    }

    public function test_it_migrates_domain_to_target_brand(): void
    {
        [$sourceBrand, $targetBrand] = $this->brands();

        $domain = $this->domain($sourceBrand, [
            'host' => 'move.example.com',
            'type' => DomainType::Frontend,
            'is_primary' => false,
        ]);

        $result = app(MigrateBrandDomain::class)->execute(
            domain: $domain,
            targetBrand: $targetBrand,
        );

        $this->assertTrue($result->migrated);
        $this->assertSame($targetBrand->id, $result->domain->brand_id);
        $this->assertFalse($result->domain->is_primary);

        $this->assertDatabaseHas('brand_domains', [
            'id' => $domain->id,
            'brand_id' => $targetBrand->id,
            'is_primary' => false,
        ]);
    }

    public function test_migrated_primary_domain_loses_primary_status_by_default(): void
    {
        [$sourceBrand, $targetBrand] = $this->brands();

        $domain = $this->domain($sourceBrand, [
            'host' => 'primary-source.example.com',
            'type' => DomainType::Frontend,
            'is_primary' => true,
        ]);

        $result = app(MigrateBrandDomain::class)->execute(
            domain: $domain,
            targetBrand: $targetBrand,
        );

        $this->assertTrue($result->migrated);
        $this->assertSame($targetBrand->id, $result->domain->brand_id);
        $this->assertFalse($result->domain->is_primary);
    }

    public function test_migrated_domain_can_become_primary_on_target_brand(): void
    {
        [$sourceBrand, $targetBrand] = $this->brands();

        $existingPrimary = $this->domain($targetBrand, [
            'host' => 'old-primary.example.com',
            'type' => DomainType::Frontend,
            'is_primary' => true,
        ]);

        $domain = $this->domain($sourceBrand, [
            'host' => 'new-primary.example.com',
            'type' => DomainType::Frontend,
            'is_primary' => true,
        ]);

        $result = app(MigrateBrandDomain::class)->execute(
            domain: $domain,
            targetBrand: $targetBrand,
            makePrimary: true,
        );

        $this->assertTrue($result->migrated);
        $this->assertTrue($result->domain->is_primary);
        $this->assertSame($targetBrand->id, $result->domain->brand_id);
        $this->assertFalse($existingPrimary->fresh()->is_primary);
    }

    public function test_primary_switch_does_not_change_other_domain_type(): void
    {
        [$sourceBrand, $targetBrand] = $this->brands();

        $adminPrimary = $this->domain($targetBrand, [
            'host' => 'admin.example.com',
            'type' => DomainType::Admin,
            'is_primary' => true,
        ]);

        $frontendDomain = $this->domain($sourceBrand, [
            'host' => 'frontend-new.example.com',
            'type' => DomainType::Frontend,
            'is_primary' => false,
        ]);

        app(MigrateBrandDomain::class)->execute(
            domain: $frontendDomain,
            targetBrand: $targetBrand,
            makePrimary: true,
        );

        $this->assertTrue($adminPrimary->fresh()->is_primary);
    }

    public function test_same_brand_migration_is_idempotent(): void
    {
        [$brand] = $this->brands();

        $domain = $this->domain($brand, [
            'host' => 'same-brand.example.com',
            'type' => DomainType::Frontend,
            'is_primary' => false,
        ]);

        $result = app(MigrateBrandDomain::class)->execute(
            domain: $domain,
            targetBrand: $brand,
        );

        $this->assertFalse($result->migrated);
        $this->assertFalse($result->plan->brandWillChange);
        $this->assertSame($brand->id, $result->domain->brand_id);
    }

    public function test_same_brand_domain_can_be_promoted_to_primary(): void
    {
        [$brand] = $this->brands();

        $domain = $this->domain($brand, [
            'host' => 'promote.example.com',
            'type' => DomainType::Frontend,
            'is_primary' => false,
        ]);

        $result = app(MigrateBrandDomain::class)->execute(
            domain: $domain,
            targetBrand: $brand,
            makePrimary: true,
        );

        $this->assertFalse($result->migrated);
        $this->assertTrue($result->domain->is_primary);
    }

    public function test_it_rejects_inactive_target_brand(): void
    {
        [$sourceBrand, $targetBrand] = $this->brands();

        $targetBrand->forceFill([
            'is_active' => false,
        ])->save();

        $domain = $this->domain($sourceBrand, [
            'host' => 'inactive-target.example.com',
        ]);

        $this->expectException(InvalidBrandDomainMigration::class);
        $this->expectExceptionMessage(
            'A domain cannot be migrated to an inactive brand.',
        );

        app(MigrateBrandDomain::class)->execute(
            domain: $domain,
            targetBrand: $targetBrand,
        );
    }

    public function test_it_rejects_inactive_domain_becoming_primary(): void
    {
        [$sourceBrand, $targetBrand] = $this->brands();

        $domain = $this->domain($sourceBrand, [
            'host' => 'inactive-domain.example.com',
            'is_active' => false,
            'is_primary' => false,
        ]);

        $this->expectException(InvalidBrandDomainMigration::class);
        $this->expectExceptionMessage(
            'An inactive domain cannot become primary during migration.',
        );

        app(MigrateBrandDomain::class)->execute(
            domain: $domain,
            targetBrand: $targetBrand,
            makePrimary: true,
        );
    }

    public function test_it_rejects_unsaved_domain(): void
    {
        [, $targetBrand] = $this->brands();

        $domain = new BrandDomain([
            'host' => 'unsaved.example.com',
            'type' => DomainType::Frontend,
            'is_active' => true,
            'is_primary' => false,
        ]);

        $this->expectException(InvalidBrandDomainMigration::class);
        $this->expectExceptionMessage(
            'The domain must exist before it can be migrated.',
        );

        app(MigrateBrandDomain::class)->execute(
            domain: $domain,
            targetBrand: $targetBrand,
        );
    }

    public function test_migration_result_can_be_converted_to_array(): void
    {
        [$sourceBrand, $targetBrand] = $this->brands();

        $domain = $this->domain($sourceBrand, [
            'host' => 'array-migration.example.com',
            'type' => DomainType::Frontend,
            'is_primary' => false,
        ]);

        $result = app(MigrateBrandDomain::class)->execute(
            domain: $domain,
            targetBrand: $targetBrand,
            makePrimary: true,
        );

        $data = $result->toArray();

        $this->assertTrue($data['migrated']);
        $this->assertSame($domain->id, $data['domain_id']);
        $this->assertSame('array-migration.example.com', $data['host']);
        $this->assertSame($targetBrand->id, $data['brand_id']);
        $this->assertSame('frontend', $data['type']);
        $this->assertTrue($data['is_primary']);
        $this->assertSame(
            $sourceBrand->id,
            $data['plan']['source_brand_id'],
        );
        $this->assertSame(
            $targetBrand->id,
            $data['plan']['target_brand_id'],
        );
    }

    /**
     * @return array{0: Brand, 1: Brand}
     */
    private function brands(): array
    {
        return [
            Brand::factory()->create([
                'is_active' => true,
                'is_primary' => true,
            ]),
            Brand::factory()->create([
                'is_active' => true,
                'is_primary' => false,
            ]),
        ];
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function domain(
        Brand $brand,
        array $attributes = [],
    ): BrandDomain {
        return BrandDomain::factory()->create(array_merge([
            'brand_id' => $brand->id,
            'host' => fake()->unique()->domainName(),
            'type' => DomainType::Frontend,
            'is_active' => true,
            'is_primary' => false,
            'force_https' => true,
        ], $attributes));
    }
}
