<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shio_periods', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('year');
            $table->string('title');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('banner_template')->nullable();
            $table->string('generated_banner')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();

            $table->unique('year');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shio_periods');
    }
};
