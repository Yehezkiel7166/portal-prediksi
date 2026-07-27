<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'brand_domain_health_histories',
            function (Blueprint $table): void {
                $table->id();

                $table->foreignId('brand_domain_id')
                    ->constrained('brand_domains')
                    ->cascadeOnDelete();

                $table->foreignId('brand_id')
                    ->constrained('brands')
                    ->cascadeOnDelete();

                $table->string('host');
                $table->string('verification_status', 32);
                $table->unsignedTinyInteger('verification_score');
                $table->json('verification_checks');
                $table->timestamp('verified_at');
                $table->timestamps();

                $table->index(
                    [
                        'brand_domain_id',
                        'verified_at',
                    ],
                    'domain_health_history_domain_verified_index',
                );

                $table->index(
                    [
                        'brand_id',
                        'verification_status',
                    ],
                    'domain_health_history_brand_status_index',
                );

                $table->index(
                    [
                        'brand_id',
                        'verified_at',
                    ],
                    'domain_health_history_brand_verified_index',
                );
            },
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'brand_domain_health_histories',
        );
    }
};
