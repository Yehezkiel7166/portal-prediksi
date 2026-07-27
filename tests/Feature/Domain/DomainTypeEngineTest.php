<?php

declare(strict_types=1);

namespace Tests\Feature\Domain;

use App\Domains\Brand\Models\Brand;
use App\Domains\Domain\Actions\ListBrandDomainsByType;
use App\Domains\Domain\Actions\ResolveBrandDomainByType;
use App\Domains\Domain\Enums\DomainType;
use App\Domains\Domain\Exceptions\InvalidDomainType;
use App\Domains\Domain\Models\BrandDomain;
use App\Domains\Domain\Policies\DomainTypePolicy;
use App\Domains\Domain\Support\DomainTypeCapabilities;
use App\Domains\Domain\Support\DomainTypeRegistry;
use App\Domains\Domain\Support\DomainTypeValidator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DomainTypeEngineTest extends TestCase
{
    use RefreshDatabase;

    public function test_validator_accepts_all_registered_enum_values(): void
    {
        $validator = app(DomainTypeValidator::class);

        foreach (DomainType::cases() as $case) {
            $this->assertSame(
                $case,
                $validator->validate($case->value),
            );
        }
    }

    public function test_validator_accepts_enum_case_names(): void
    {
        $validator = app(DomainTypeValidator::class);

        foreach (DomainType::cases() as $case) {
            $this->assertSame(
                $case,
                $validator->validate($case->name),
            );
        }
    }

    public function test_validator_is_case_insensitive(): void
    {
        $case = DomainType::cases()[0];

        $resolved = app(DomainTypeValidator::class)
            ->validate(strtoupper($case->value));

        $this->assertSame($case, $resolved);
    }

    public function test_validator_rejects_unknown_domain_type(): void
    {
        $this->expectException(InvalidDomainType::class);
        $this->expectExceptionMessage('Unsupported domain type');

        app(DomainTypeValidator::class)
            ->validate('unknown-domain-type');
    }

    public function test_supports_returns_false_for_unknown_type(): void
    {
        $validator = app(DomainTypeValidator::class);

        $this->assertFalse(
            $validator->supports('unknown-domain-type')
        );
    }

    public function test_supported_values_match_enum_cases(): void
    {
        $expected = array_map(
            static fn (DomainType $case): string => $case->value,
            DomainType::cases(),
        );

        $this->assertSame(
            $expected,
            app(DomainTypeValidator::class)->supportedValues(),
        );
    }

    public function test_capabilities_return_complete_boolean_map(): void
    {
        $capabilities = app(DomainTypeCapabilities::class);

        foreach (DomainType::cases() as $case) {
            $map = $capabilities->for($case);

            $this->assertSame([
                'serves_public_content',
                'serves_admin_panel',
                'serves_api',
                'serves_static_assets',
                'supports_canonical',
                'supports_preview',
                'requires_authentication',
                'indexable',
            ], array_keys($map));

            foreach ($map as $value) {
                $this->assertIsBool($value);
            }
        }
    }

    public function test_frontend_capabilities_when_frontend_type_exists(): void
    {
        $frontend = collect(DomainType::cases())
            ->first(fn (DomainType $type): bool => strtolower($type->value) === 'frontend'
            );

        if (! $frontend instanceof DomainType) {
            $this->markTestSkipped('Frontend domain type is not registered.');
        }

        $capabilities = app(DomainTypeCapabilities::class);

        $this->assertTrue(
            $capabilities->servesPublicContent($frontend)
        );

        $this->assertTrue(
            $capabilities->supportsCanonical($frontend)
        );

        $this->assertTrue(
            $capabilities->isIndexable($frontend)
        );

        $this->assertFalse(
            $capabilities->requiresAuthentication($frontend)
        );
    }

    public function test_admin_capabilities_when_admin_type_exists(): void
    {
        $admin = collect(DomainType::cases())
            ->first(fn (DomainType $type): bool => strtolower($type->value) === 'admin'
            );

        if (! $admin instanceof DomainType) {
            $this->markTestSkipped('Admin domain type is not registered.');
        }

        $capabilities = app(DomainTypeCapabilities::class);

        $this->assertTrue(
            $capabilities->servesAdminPanel($admin)
        );

        $this->assertTrue(
            $capabilities->requiresAuthentication($admin)
        );

        $this->assertFalse(
            $capabilities->isIndexable($admin)
        );
    }

    public function test_policy_matches_domain_capabilities(): void
    {
        $policy = app(DomainTypePolicy::class);
        $capabilities = app(DomainTypeCapabilities::class);

        foreach (DomainType::cases() as $type) {
            $this->assertSame(
                $capabilities->supportsCanonical($type),
                $policy->canBeCanonical($type),
            );

            $this->assertSame(
                $capabilities->isIndexable($type),
                $policy->canBeIndexed($type),
            );

            $this->assertSame(
                ! $capabilities->isIndexable($type),
                $policy->shouldForceNoIndex($type),
            );
        }
    }

    public function test_resolver_returns_primary_domain_first(): void
    {
        $brand = Brand::factory()->create();
        $type = DomainType::cases()[0];

        BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'secondary-type.example.test',
                'type' => $type,
                'is_active' => true,
                'is_primary' => false,
                'sort_order' => 0,
            ]);

        $primary = BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'primary-type.example.test',
                'type' => $type,
                'is_active' => true,
                'is_primary' => true,
                'sort_order' => 100,
            ]);

        $resolved = app(ResolveBrandDomainByType::class)
            ->execute($brand, $type);

        $this->assertNotNull($resolved);
        $this->assertTrue($primary->is($resolved));
    }

    public function test_resolver_can_require_primary_domain(): void
    {
        $brand = Brand::factory()->create();
        $type = DomainType::cases()[0];

        BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'non-primary-type.example.test',
                'type' => $type,
                'is_active' => true,
                'is_primary' => false,
            ]);

        $resolved = app(ResolveBrandDomainByType::class)
            ->execute($brand, $type, true);

        $this->assertNull($resolved);
    }

    public function test_resolver_ignores_inactive_domains(): void
    {
        $brand = Brand::factory()->create();
        $type = DomainType::cases()[0];

        BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'inactive-type.example.test',
                'type' => $type,
                'is_active' => false,
                'is_primary' => true,
            ]);

        $resolved = app(ResolveBrandDomainByType::class)
            ->execute($brand, $type);

        $this->assertNull($resolved);
    }

    public function test_resolver_does_not_cross_brand_boundary(): void
    {
        $firstBrand = Brand::factory()->create();
        $secondBrand = Brand::factory()->create();
        $type = DomainType::cases()[0];

        BrandDomain::factory()
            ->for($secondBrand)
            ->create([
                'host' => 'other-brand-type.example.test',
                'type' => $type,
                'is_active' => true,
                'is_primary' => true,
            ]);

        $resolved = app(ResolveBrandDomainByType::class)
            ->execute($firstBrand, $type);

        $this->assertNull($resolved);
    }

    public function test_list_action_only_returns_requested_type(): void
    {
        $brand = Brand::factory()->create();
        $types = DomainType::cases();
        $requestedType = $types[0];

        $matching = BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'matching-type.example.test',
                'type' => $requestedType,
                'is_active' => true,
            ]);

        if (count($types) > 1) {
            BrandDomain::factory()
                ->for($brand)
                ->create([
                    'host' => 'different-type.example.test',
                    'type' => $types[1],
                    'is_active' => true,
                ]);
        }

        $results = app(ListBrandDomainsByType::class)
            ->execute($brand, $requestedType);

        $this->assertCount(1, $results);
        $this->assertTrue($matching->is($results->first()));
    }

    public function test_list_action_excludes_inactive_domains_by_default(): void
    {
        $brand = Brand::factory()->create();
        $type = DomainType::cases()[0];

        BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'active-list.example.test',
                'type' => $type,
                'is_active' => true,
            ]);

        BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'inactive-list.example.test',
                'type' => $type,
                'is_active' => false,
            ]);

        $results = app(ListBrandDomainsByType::class)
            ->execute($brand, $type);

        $this->assertCount(1, $results);
        $this->assertTrue($results->first()->is_active);
    }

    public function test_list_action_can_include_inactive_domains(): void
    {
        $brand = Brand::factory()->create();
        $type = DomainType::cases()[0];

        BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'active-all.example.test',
                'type' => $type,
                'is_active' => true,
            ]);

        BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'inactive-all.example.test',
                'type' => $type,
                'is_active' => false,
            ]);

        $results = app(ListBrandDomainsByType::class)
            ->execute($brand, $type, false);

        $this->assertCount(2, $results);
    }

    public function test_registry_contains_every_domain_type(): void
    {
        $registry = app(DomainTypeRegistry::class)->all();

        $this->assertCount(count(DomainType::cases()), $registry);

        foreach (DomainType::cases() as $type) {
            $this->assertArrayHasKey($type->value, $registry);

            $this->assertSame(
                $type->value,
                $registry[$type->value]['value'],
            );
        }
    }

    public function test_registry_provides_human_readable_labels(): void
    {
        $registry = app(DomainTypeRegistry::class);

        foreach (DomainType::cases() as $type) {
            $this->assertNotSame(
                '',
                $registry->label($type),
            );
        }
    }
}
