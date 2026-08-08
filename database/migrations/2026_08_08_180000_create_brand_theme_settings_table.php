<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brand_theme_settings', function (Blueprint $table): void {
            $table->id();

            $table->unsignedBigInteger('brand_id')
                ->unique();

            $table->string('theme_slug', 100)
                ->default('midnight-gold');

            $table->string('background_mode', 20)
                ->default('theme');

            $table->string('background_image')
                ->nullable();

            $table->string('background_size', 20)
                ->default('cover');

            $table->string('background_position', 20)
                ->default('center');

            $table->boolean('background_repeat')
                ->default(false);

            $table->boolean('background_fixed')
                ->default(false);

            $table->boolean('overlay_enabled')
                ->default(false);

            $table->string('overlay_color', 20)
                ->default('#000000');

            $table->decimal(
                'overlay_opacity',
                4,
                2,
            )->default(0);

            $table->string('component_style', 30)
                ->default('solid');

            $table->decimal(
                'component_opacity',
                4,
                2,
            )->default(1);

            $table->unsignedSmallInteger('component_blur')
                ->default(0);

            $table->json('tokens')
                ->nullable();

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();

            $table->index([
                'brand_id',
                'is_active',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brand_theme_settings');
    }
};
