<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();

            $table->string('media_source')->default('upload');
            $table->string('media_path')->nullable();
            $table->text('media_url')->nullable();
            $table->text('embed_url')->nullable();
            $table->string('focal_point')->default('center');

            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['status', 'published_at']);
            $table->index('sort_order');
            $table->index('media_source');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
