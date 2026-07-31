<?php

declare(strict_types=1);

namespace Tests\Feature\Production;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

final class BootstrapBrandOneProductionCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_is_dry_run_without_apply_option(): void
    {
        $exitCode = Artisan::call(
            'brand-one:production-bootstrap',
            [
                '--brand-name' => 'SANTOTO4D',
                '--domain' => 'santoto4d-prediksi.site',
                '--admin-name' => 'Test Administrator',
                '--admin-email' => 'admin@example.test',
                '--force' => true,
            ]
        );

        self::assertSame(0, $exitCode);
        self::assertStringContainsString(
            'DRY RUN ONLY',
            Artisan::output()
        );
        self::assertSame(0, DB::table('brands')->count());
    }

    public function test_command_applies_an_idempotent_bootstrap(): void
    {
        putenv('TEST_BRAND_ONE_ADMIN_PASSWORD=StrongTestPassword123!');

        try {
            $arguments = [
                '--brand-name' => 'SANTOTO4D',
                '--domain' => 'santoto4d-prediksi.site',
                '--admin-name' => 'Test Administrator',
                '--admin-email' => 'admin@example.test',
                '--password-env' => 'TEST_BRAND_ONE_ADMIN_PASSWORD',
                '--apply' => true,
                '--force' => true,
            ];

            $firstExitCode = Artisan::call(
                'brand-one:production-bootstrap',
                $arguments
            );

            self::assertSame(
                0,
                $firstExitCode,
                Artisan::output()
            );

            $secondExitCode = Artisan::call(
                'brand-one:production-bootstrap',
                $arguments
            );

            self::assertSame(
                0,
                $secondExitCode,
                Artisan::output()
            );

            self::assertSame(
                1,
                DB::table('brands')
                    ->where('name', 'SANTOTO4D')
                    ->count()
            );

            $domainColumn = collect(['host', 'hostname', 'domain'])
                ->first(
                    fn (string $column): bool =>
                        Schema::hasColumn('brand_domains', $column)
                );

            self::assertNotNull($domainColumn);

            self::assertSame(
                1,
                DB::table('brand_domains')
                    ->where(
                        $domainColumn,
                        'santoto4d-prediksi.site'
                    )
                    ->count()
            );

            self::assertSame(
                1,
                DB::table('site_configurations')->count()
            );

            self::assertSame(
                1,
                DB::table('users')
                    ->where('email', 'admin@example.test')
                    ->count()
            );
        } finally {
            putenv('TEST_BRAND_ONE_ADMIN_PASSWORD');
        }
    }
}
