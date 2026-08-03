<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_banners', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('brand_id')->constrained('brands')->cascadeOnDelete();
            $table->string('title', 255);
            $table->text('subtitle')->nullable();
            $table->string('desktop_image_path', 2048);
            $table->string('mobile_image_path', 2048)->nullable();
            $table->string('cta_label', 120)->nullable();
            $table->text('cta_url')->nullable();
            $table->string('focal_point', 32)->default('center');
            $table->string('status', 24)->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(
                [
                    'brand_id',
                    'status',
                    'published_at',
                    'expires_at',
                    'sort_order',
                ],
                'homepage_banners_public_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_banners');
    }
};
