<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('markets', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 100);
            $table->string('slug', 120)->unique();
            $table->string('timezone', 100)->default('Asia/Jakarta');
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(
                ['is_active', 'sort_order'],
                'markets_active_sort_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('markets');
    }
};
