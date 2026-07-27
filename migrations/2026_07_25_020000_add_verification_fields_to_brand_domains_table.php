<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('brand_domains', function (Blueprint $table): void {
            $table->string('verification_status', 32)
                ->nullable()
                ->after('settings');

            $table->unsignedTinyInteger('verification_score')
                ->nullable()
                ->after('verification_status');

            $table->json('verification_checks')
                ->nullable()
                ->after('verification_score');

            $table->timestamp('verified_at')
                ->nullable()
                ->after('verification_checks');

            $table->index(
                ['is_active', 'verification_status'],
                'brand_domains_active_verification_status_index',
            );

            $table->index(
                'verified_at',
                'brand_domains_verified_at_index',
            );
        });
    }

    public function down(): void
    {
        Schema::table('brand_domains', function (Blueprint $table): void {
            $table->dropIndex(
                'brand_domains_active_verification_status_index',
            );

            $table->dropIndex(
                'brand_domains_verified_at_index',
            );

            $table->dropColumn([
                'verification_status',
                'verification_score',
                'verification_checks',
                'verified_at',
            ]);
        });
    }
};
