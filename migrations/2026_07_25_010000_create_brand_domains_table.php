<?php

declare(strict_types=1);

use App\Domains\Domain\Enums\DomainType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brand_domains', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('brand_id')
                ->constrained('brands')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('host')->unique();
            $table->string('type', 30)
                ->default(DomainType::Frontend->value);

            $table->boolean('is_primary')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('force_https')->default(true);

            $table->unsignedInteger('sort_order')->default(0);
            $table->json('settings')->nullable();

            $table->timestamps();

            $table->index(
                ['brand_id', 'type', 'is_active'],
                'brand_domains_brand_type_active_idx',
            );

            $table->index(
                ['brand_id', 'type', 'is_primary'],
                'brand_domains_brand_type_primary_idx',
            );

            $table->index(
                ['is_active', 'host'],
                'brand_domains_active_host_idx',
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brand_domains');
    }
};
