<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shio_numbers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('shio_period_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');
            $table->json('numbers');
            $table->string('icon')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shio_numbers');
    }
};
