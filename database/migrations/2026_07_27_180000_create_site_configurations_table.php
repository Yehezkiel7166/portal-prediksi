<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_configurations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('brand_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('site_name')->nullable();
            $table->string('tagline')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('favicon_url')->nullable();
            $table->string('default_seo_title')->nullable();
            $table->text('default_seo_description')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone', 50)->nullable();
            $table->string('whatsapp_number', 50)->nullable();
            $table->json('social_links')->nullable();
            $table->text('footer_text')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_configurations');
    }
};
