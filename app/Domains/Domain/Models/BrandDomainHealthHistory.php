<?php

declare(strict_types=1);

namespace App\Domains\Domain\Models;

use App\Domains\Brand\Models\Brand;
use App\Domains\Domain\Enums\DomainVerificationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class BrandDomainHealthHistory extends Model
{
    protected $table =
        'brand_domain_health_histories';

    protected $fillable = [
        'brand_domain_id',
        'brand_id',
        'host',
        'verification_status',
        'verification_score',
        'verification_checks',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'verification_status' => DomainVerificationStatus::class,

            'verification_score' => 'integer',
            'verification_checks' => 'array',
            'verified_at' => 'immutable_datetime',
        ];
    }

    /**
     * @return BelongsTo<BrandDomain, $this>
     */
    public function domain(): BelongsTo
    {
        return $this->belongsTo(
            BrandDomain::class,
            'brand_domain_id',
        );
    }

    /**
     * @return BelongsTo<Brand, $this>
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function hasCriticalIssue(): bool
    {
        if (
            $this->verification_status
            === DomainVerificationStatus::Critical
        ) {
            return true;
        }

        foreach ($this->verification_checks ?? [] as $check) {
            if (
                ($check['status'] ?? null)
                === DomainVerificationStatus::Critical->value
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function issues(): array
    {
        return array_values(
            array_filter(
                $this->verification_checks ?? [],
                static fn (array $check): bool => ($check['status'] ?? null)
                    !== DomainVerificationStatus::Healthy->value,
            ),
        );
    }
}
