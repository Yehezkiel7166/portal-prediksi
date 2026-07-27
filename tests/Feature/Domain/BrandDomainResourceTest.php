<?php

declare(strict_types=1);

namespace Tests\Feature\Domain;

use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\Domain\Enums\DomainType;
use App\Domains\Domain\Models\BrandDomain;
use App\Filament\Resources\BrandDomainResource;
use App\Filament\Resources\BrandDomainResource\Actions\DomainTypeOptions;
use App\Filament\Resources\BrandDomainResource\Actions\NormalizeBrandDomainFormData;
use App\Filament\Resources\BrandDomainResource\Pages\CreateBrandDomain;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use Tests\TestCase;

final class BrandDomainResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_uses_brand_domain_model(): void
    {
        $this->assertSame(
            BrandDomain::class,
            BrandDomainResource::getModel(),
        );
    }

    public function test_resource_registers_crud_pages(): void
    {
        $pages = BrandDomainResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_domain_type_options_include_all_enum_cases(): void
    {
        $options = app(DomainTypeOptions::class)->execute();

        foreach (DomainType::cases() as $type) {
            $this->assertArrayHasKey($type->value, $options);
        }
    }

    public function test_normalizer_assigns_current_brand(): void
    {
        $brand = Brand::factory()->create();

        app(BrandContext::class)->set($brand);

        $data = app(NormalizeBrandDomainFormData::class)
            ->execute([
                'host' => 'HTTPS://Example.COM/path',
                'type' => DomainType::Frontend->value,
                'is_active' => true,
                'is_primary' => false,
                'force_https' => true,
                'settings' => [],
            ]);

        $this->assertSame(
            $brand->getKey(),
            $data['brand_id'],
        );

        $this->assertSame('example.com', $data['host']);
        $this->assertTrue($data['force_https']);
    }

    public function test_normalizer_rejects_missing_brand_context(): void
    {
        app(BrandContext::class)->clear();

        $this->expectException(
            ValidationException::class,
        );

        app(NormalizeBrandDomainFormData::class)
            ->execute([
                'host' => 'example.com',
                'type' => DomainType::Frontend->value,
            ]);
    }

    public function test_normalizer_rejects_invalid_domain_type(): void
    {
        $brand = Brand::factory()->create();

        app(BrandContext::class)->set($brand);

        $this->expectException(
            ValidationException::class,
        );

        app(NormalizeBrandDomainFormData::class)
            ->execute([
                'host' => 'example.com',
                'type' => 'invalid',
            ]);
    }

    public function test_inactive_domain_cannot_remain_primary(): void
    {
        $brand = Brand::factory()->create();

        app(BrandContext::class)->set($brand);

        $data = app(NormalizeBrandDomainFormData::class)
            ->execute([
                'host' => 'example.com',
                'type' => DomainType::Frontend->value,
                'is_active' => false,
                'is_primary' => true,
            ]);

        $this->assertFalse($data['is_primary']);
    }

    public function test_normalizer_applies_https_defaults(): void
    {
        $brand = Brand::factory()->create();

        app(BrandContext::class)->set($brand);

        $data = app(NormalizeBrandDomainFormData::class)
            ->execute([
                'host' => 'example.com',
                'type' => DomainType::Frontend->value,
                'settings' => [],
            ]);

        $this->assertTrue($data['force_https']);
        $this->assertTrue(
            $data['settings']['send_hsts'],
        );
        $this->assertSame(
            31536000,
            $data['settings']['hsts_max_age'],
        );
        $this->assertSame(
            308,
            $data['settings']['https_redirect_status'],
        );
    }

    public function test_preload_is_disabled_without_include_subdomains(): void
    {
        $brand = Brand::factory()->create();

        app(BrandContext::class)->set($brand);

        $data = app(NormalizeBrandDomainFormData::class)
            ->execute([
                'host' => 'example.com',
                'type' => DomainType::Frontend->value,
                'settings' => [
                    'hsts_include_subdomains' => false,
                    'hsts_preload' => true,
                ],
            ]);

        $this->assertFalse(
            $data['settings']['hsts_preload'],
        );
    }

    public function test_resource_query_only_returns_current_brand_domains(): void
    {
        $brandA = Brand::factory()->create();
        $brandB = Brand::factory()->create();

        $domainA = BrandDomain::factory()
            ->for($brandA)
            ->create();

        BrandDomain::factory()
            ->for($brandB)
            ->create();

        app(BrandContext::class)->set($brandA);

        $results = BrandDomainResource::getEloquentQuery()
            ->get();

        $this->assertCount(1, $results);
        $this->assertTrue(
            $domainA->is($results->first()),
        );
    }

    public function test_resource_query_returns_nothing_without_brand_context(): void
    {
        BrandDomain::factory()->create();

        app(BrandContext::class)->clear();

        $this->assertCount(
            0,
            BrandDomainResource::getEloquentQuery()->get(),
        );
    }

    public function test_admin_can_open_domain_resource(): void
    {
        $brand = Brand::factory()->create();

        app(BrandContext::class)->set($brand);

        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get('/admin/brand-domains')
            ->assertOk();
    }

    public function test_regular_user_cannot_open_domain_resource(): void
    {
        $brand = Brand::factory()->create();

        app(BrandContext::class)->set($brand);

        $user = User::factory()->create([
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->get('/admin/brand-domains')
            ->assertForbidden();
    }

    public function test_admin_can_open_create_page(): void
    {
        $brand = Brand::factory()->create();

        app(BrandContext::class)->set($brand);

        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get('/admin/brand-domains/create')
            ->assertOk();
    }

    public function test_admin_can_open_edit_page_for_current_brand(): void
    {
        $brand = Brand::factory()->create();

        app(BrandContext::class)->set($brand);

        $domain = BrandDomain::factory()
            ->for($brand)
            ->create();

        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(
                "/admin/brand-domains/{$domain->getKey()}/edit",
            )
            ->assertOk();
    }

    public function test_other_brand_domain_cannot_be_opened(): void
    {
        $contextBrand = Brand::factory()->create();
        $otherBrand = Brand::factory()->create();

        app(BrandContext::class)->set($contextBrand);

        $domain = BrandDomain::factory()
            ->for($otherBrand)
            ->create();

        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(
                "/admin/brand-domains/{$domain->getKey()}/edit",
            )
            ->assertNotFound();
    }

    public function test_list_page_contains_create_action(): void
    {
        $contents = file_get_contents(
            app_path(
                'Filament/Resources/BrandDomainResource/Pages/ListBrandDomains.php',
            ),
        );

        $this->assertStringContainsString(
            'CreateAction::make()',
            $contents,
        );
    }

    public function test_create_page_creates_normalized_domain(): void
    {
        $brand = Brand::factory()->create();

        app(BrandContext::class)->set($brand);

        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin);

        Livewire::test(CreateBrandDomain::class)
            ->fillForm([
                'host' => 'HTTPS://Frontend.Example.TEST/path',
                'type' => DomainType::Frontend->value,
                'sort_order' => 1,
                'is_active' => true,
                'is_primary' => false,
                'force_https' => true,
                'settings' => [
                    'send_hsts' => true,
                    'hsts_max_age' => 31536000,
                    'hsts_include_subdomains' => false,
                    'hsts_preload' => false,
                    'https_redirect_status' => 308,
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('brand_domains', [
            'brand_id' => $brand->getKey(),
            'host' => 'frontend.example.test',
            'type' => DomainType::Frontend->value,
            'force_https' => true,
            'is_active' => true,
        ]);
    }

    public function test_create_primary_domain_removes_previous_primary(): void
    {
        $brand = Brand::factory()->create();

        app(BrandContext::class)->set($brand);

        $oldPrimary = BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'old.example.test',
                'type' => DomainType::Frontend,
                'is_active' => true,
                'is_primary' => true,
            ]);

        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin);

        Livewire::test(CreateBrandDomain::class)
            ->fillForm([
                'host' => 'new.example.test',
                'type' => DomainType::Frontend->value,
                'sort_order' => 0,
                'is_active' => true,
                'is_primary' => true,
                'force_https' => true,
                'settings' => [
                    'send_hsts' => true,
                    'hsts_max_age' => 31536000,
                    'hsts_include_subdomains' => false,
                    'hsts_preload' => false,
                    'https_redirect_status' => 308,
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertFalse(
            $oldPrimary->fresh()->is_primary,
        );

        $this->assertDatabaseHas('brand_domains', [
            'brand_id' => $brand->getKey(),
            'host' => 'new.example.test',
            'type' => DomainType::Frontend->value,
            'is_primary' => true,
        ]);
    }
}
