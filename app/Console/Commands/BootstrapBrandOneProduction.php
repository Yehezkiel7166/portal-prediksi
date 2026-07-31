<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

final class BootstrapBrandOneProduction extends Command
{
    protected $signature = 'brand-one:production-bootstrap
        {--brand-name=SANTOTO4D : Canonical Brand 1 name}
        {--domain=santoto4d-prediksi.site : Canonical production hostname}
        {--admin-name=Administrator : Production administrator name}
        {--admin-email= : Production administrator email}
        {--password-env=BRAND_ONE_ADMIN_PASSWORD : Environment variable containing the administrator password}
        {--apply : Apply database mutations}
        {--force : Allow execution outside production}';

    protected $description =
        'Idempotently bootstrap canonical Brand 1 production identity, domain, configuration, administrator, and tenant ownership';

    public function handle(): int
    {
        try {
            $this->assertEnvironment();
            $this->assertRequiredSchema();

            $brandName = trim((string) $this->option('brand-name'));
            $domain = $this->normalizeDomain((string) $this->option('domain'));
            $adminName = trim((string) $this->option('admin-name'));
            $adminEmail = mb_strtolower(trim((string) $this->option('admin-email')));
            $passwordEnvironmentVariable =
                trim((string) $this->option('password-env'));

            $this->assertInputs(
                $brandName,
                $domain,
                $adminName,
                $adminEmail,
                $passwordEnvironmentVariable
            );

            $this->displayPlan(
                $brandName,
                $domain,
                $adminName,
                $adminEmail,
                $passwordEnvironmentVariable
            );

            if (! (bool) $this->option('apply')) {
                $this->warn('DRY RUN ONLY. No database records were changed.');
                $this->line('Run again with --apply after creating and verifying a production backup.');

                return self::SUCCESS;
            }

            $password = getenv($passwordEnvironmentVariable);

            if (! is_string($password) || mb_strlen($password) < 12) {
                throw new RuntimeException(
                    "Environment variable {$passwordEnvironmentVariable} must contain a password of at least 12 characters."
                );
            }

            $result = DB::transaction(function () use (
                $brandName,
                $domain,
                $adminName,
                $adminEmail,
                $password
            ): array {
                $brandId = $this->upsertBrand($brandName, $domain);

                $domainId = $this->upsertPrimaryDomain($brandId, $domain);

                $siteConfigurationId =
                    $this->upsertSiteConfiguration($brandId, $brandName);

                $administratorId = $this->upsertAdministrator(
                    $adminName,
                    $adminEmail,
                    $password
                );

                $ownership = $this->remediateTenantOwnership($brandId);

                return [
                    'brand_id' => $brandId,
                    'domain_id' => $domainId,
                    'site_configuration_id' => $siteConfigurationId,
                    'administrator_id' => $administratorId,
                    'ownership' => $ownership,
                ];
            }, 3);

            $this->newLine();
            $this->info('Brand 1 production bootstrap completed.');

            $this->table(
                ['Item', 'Value'],
                [
                    ['Brand ID', (string) $result['brand_id']],
                    ['Domain ID', (string) $result['domain_id']],
                    [
                        'Site Configuration ID',
                        (string) $result['site_configuration_id'],
                    ],
                    [
                        'Administrator ID',
                        (string) $result['administrator_id'],
                    ],
                    [
                        'Results remediated',
                        (string) $result['ownership']['results'],
                    ],
                    [
                        'Predictions remediated',
                        (string) $result['ownership']['predictions'],
                    ],
                    [
                        'Live draws remediated',
                        (string) $result['ownership']['live_draws'],
                    ],
                ]
            );

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }
    }

    private function assertEnvironment(): void
    {
        if (app()->environment('production')) {
            return;
        }

        if ((bool) $this->option('force')) {
            $this->warn(
                'Execution outside production was explicitly allowed with --force.'
            );

            return;
        }

        throw new RuntimeException(
            'This command is production-only. Use --force only for isolated automated tests.'
        );
    }

    private function assertRequiredSchema(): void
    {
        $requirements = [
            'brands' => ['id', 'name'],
            'brand_domains' => ['id', 'brand_id'],
            'site_configurations' => ['id', 'brand_id'],
            'users' => ['id', 'name', 'email', 'password'],
            'results' => ['brand_id'],
            'predictions' => ['brand_id'],
            'live_draws' => ['brand_id'],
        ];

        foreach ($requirements as $table => $columns) {
            if (! Schema::hasTable($table)) {
                throw new RuntimeException(
                    "Required table does not exist: {$table}"
                );
            }

            foreach ($columns as $column) {
                if (! Schema::hasColumn($table, $column)) {
                    throw new RuntimeException(
                        "Required column does not exist: {$table}.{$column}"
                    );
                }
            }
        }
    }

    private function assertInputs(
        string $brandName,
        string $domain,
        string $adminName,
        string $adminEmail,
        string $passwordEnvironmentVariable
    ): void {
        if ($brandName === '') {
            throw new RuntimeException('Brand name cannot be empty.');
        }

        if ($domain === '' || filter_var($domain, FILTER_VALIDATE_DOMAIN) === false) {
            throw new RuntimeException('A valid canonical domain is required.');
        }

        if ($adminName === '') {
            throw new RuntimeException('Administrator name cannot be empty.');
        }

        if (filter_var($adminEmail, FILTER_VALIDATE_EMAIL) === false) {
            throw new RuntimeException(
                'A valid --admin-email option is required.'
            );
        }

        if (
            $passwordEnvironmentVariable === ''
            || preg_match('/^[A-Z][A-Z0-9_]*$/', $passwordEnvironmentVariable) !== 1
        ) {
            throw new RuntimeException(
                'The --password-env value must be a valid uppercase environment variable name.'
            );
        }
    }

    private function displayPlan(
        string $brandName,
        string $domain,
        string $adminName,
        string $adminEmail,
        string $passwordEnvironmentVariable
    ): void {
        $this->table(
            ['Operation', 'Target'],
            [
                ['Canonical brand', $brandName],
                ['Primary frontend domain', $domain],
                ['Site configuration', $brandName],
                ['Administrator name', $adminName],
                ['Administrator email', $adminEmail],
                [
                    'Password source',
                    "Environment variable {$passwordEnvironmentVariable}",
                ],
                [
                    'Ownership remediation',
                    'results, predictions, live_draws where brand_id is NULL',
                ],
            ]
        );
    }

    private function upsertBrand(string $brandName, string $domain): int
    {
        $query = DB::table('brands');

        $brand = $query
            ->whereRaw('LOWER(name) = ?', [mb_strtolower($brandName)])
            ->first();

        $brandSlug = Str::slug($brandName);
        $brandCode = Str::upper(
            Str::replace('-', '_', $brandSlug)
        );

        $values = [
            'name' => $brandName,
            'code' => $brandCode,
        ];

        $this->putIfColumnExists(
            $values,
            'brands',
            'slug',
            $brandSlug
        );
        $this->putIfColumnExists($values, 'brands', 'domain', $domain);
        $this->putIfColumnExists($values, 'brands', 'is_active', true);
        $this->putIfColumnExists($values, 'brands', 'is_primary', true);
        $this->putTimestampValues($values, 'brands', $brand === null);

        if ($brand !== null) {
            DB::table('brands')
                ->where('id', $brand->id)
                ->update($values);

            $brandId = (int) $brand->id;
        } else {
            $brandId = (int) DB::table('brands')->insertGetId($values);
        }

        if (Schema::hasColumn('brands', 'is_primary')) {
            DB::table('brands')
                ->where('id', '<>', $brandId)
                ->update(['is_primary' => false]);
        }

        return $brandId;
    }

    private function upsertPrimaryDomain(int $brandId, string $domain): int
    {
        $domainColumn = $this->firstExistingColumn(
            'brand_domains',
            ['host', 'hostname', 'domain']
        );

        if ($domainColumn === null) {
            throw new RuntimeException(
                'No supported domain column exists on brand_domains.'
            );
        }

        $existing = DB::table('brand_domains')
            ->whereRaw("LOWER({$domainColumn}) = ?", [mb_strtolower($domain)])
            ->first();

        $values = [
            'brand_id' => $brandId,
            $domainColumn => $domain,
        ];

        $this->putIfColumnExists(
            $values,
            'brand_domains',
            'type',
            'frontend'
        );
        $this->putIfColumnExists(
            $values,
            'brand_domains',
            'is_primary',
            true
        );
        $this->putIfColumnExists(
            $values,
            'brand_domains',
            'is_active',
            true
        );
        $this->putIfColumnExists(
            $values,
            'brand_domains',
            'status',
            'critical'
        );
        $this->putIfColumnExists(
            $values,
            'brand_domains',
            'scheme',
            'https'
        );
        $this->putTimestampValues(
            $values,
            'brand_domains',
            $existing === null
        );

        if ($existing !== null) {
            DB::table('brand_domains')
                ->where('id', $existing->id)
                ->update($values);

            $domainId = (int) $existing->id;
        } else {
            $domainId =
                (int) DB::table('brand_domains')->insertGetId($values);
        }

        if (Schema::hasColumn('brand_domains', 'is_primary')) {
            DB::table('brand_domains')
                ->where('brand_id', $brandId)
                ->where('id', '<>', $domainId)
                ->update(['is_primary' => false]);
        }

        return $domainId;
    }

    private function upsertSiteConfiguration(
        int $brandId,
        string $brandName
    ): int {
        $existing = DB::table('site_configurations')
            ->where('brand_id', $brandId)
            ->first();

        $values = [
            'brand_id' => $brandId,
        ];

        $this->putIfColumnExists(
            $values,
            'site_configurations',
            'site_name',
            $brandName
        );
        $this->putIfColumnExists(
            $values,
            'site_configurations',
            'tagline',
            'Portal informasi dan prediksi'
        );
        $this->putIfColumnExists(
            $values,
            'site_configurations',
            'default_seo_title',
            $brandName
        );
        $this->putIfColumnExists(
            $values,
            'site_configurations',
            'default_seo_description',
            'Informasi terbaru, jadwal, hasil, prediksi, dan alat togel.'
        );
        $this->putIfColumnExists(
            $values,
            'site_configurations',
            'footer_text',
            "© {$brandName}"
        );
        $this->putIfColumnExists(
            $values,
            'site_configurations',
            'is_active',
            true
        );
        $this->putTimestampValues(
            $values,
            'site_configurations',
            $existing === null
        );

        if ($existing !== null) {
            DB::table('site_configurations')
                ->where('id', $existing->id)
                ->update($values);

            return (int) $existing->id;
        }

        return (int) DB::table('site_configurations')->insertGetId($values);
    }

    private function upsertAdministrator(
        string $name,
        string $email,
        string $password
    ): int {
        $existing = DB::table('users')
            ->whereRaw('LOWER(email) = ?', [$email])
            ->first();

        $values = [
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ];

        $this->putIfColumnExists(
            $values,
            'users',
            'is_admin',
            true
        );
        $this->putIfColumnExists(
            $values,
            'users',
            'email_verified_at',
            now()
        );
        $this->putTimestampValues($values, 'users', $existing === null);

        if ($existing !== null) {
            DB::table('users')
                ->where('id', $existing->id)
                ->update($values);

            return (int) $existing->id;
        }

        return (int) DB::table('users')->insertGetId($values);
    }

    /**
     * @return array{results:int,predictions:int,live_draws:int}
     */
    private function remediateTenantOwnership(int $brandId): array
    {
        return [
            'results' => DB::table('results')
                ->whereNull('brand_id')
                ->update(['brand_id' => $brandId]),
            'predictions' => DB::table('predictions')
                ->whereNull('brand_id')
                ->update(['brand_id' => $brandId]),
            'live_draws' => DB::table('live_draws')
                ->whereNull('brand_id')
                ->update(['brand_id' => $brandId]),
        ];
    }

    /**
     * @param array<string, mixed> $values
     */
    private function putIfColumnExists(
        array &$values,
        string $table,
        string $column,
        mixed $value
    ): void {
        if (Schema::hasColumn($table, $column)) {
            $values[$column] = $value;
        }
    }

    /**
     * @param array<string, mixed> $values
     */
    private function putTimestampValues(
        array &$values,
        string $table,
        bool $creating
    ): void {
        if (Schema::hasColumn($table, 'updated_at')) {
            $values['updated_at'] = now();
        }

        if ($creating && Schema::hasColumn($table, 'created_at')) {
            $values['created_at'] = now();
        }
    }

    /**
     * @param list<string> $candidates
     */
    private function firstExistingColumn(
        string $table,
        array $candidates
    ): ?string {
        foreach ($candidates as $candidate) {
            if (Schema::hasColumn($table, $candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    private function normalizeDomain(string $domain): string
    {
        $domain = mb_strtolower(trim($domain));
        $domain = preg_replace('#^https?://#', '', $domain) ?? $domain;
        $domain = explode('/', $domain, 2)[0];
        $domain = explode(':', $domain, 2)[0];

        return rtrim($domain, '.');
    }
}
