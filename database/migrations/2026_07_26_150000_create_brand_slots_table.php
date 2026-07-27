<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brand_slots', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->string('provider_name', 120);
            $table->string('game_name', 180);
            $table->string('slug', 180);
            $table->text('image_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_published')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['brand_id', 'slug']);
            $table->index(['brand_id', 'is_active', 'is_published', 'sort_order'], 'brand_slots_public_index');
        });
    }

    public function down(): void { Schema::dropIfExists('brand_slots'); }
};
