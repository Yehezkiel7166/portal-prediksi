<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'paito_cell_colors',
            function (Blueprint $table): void {
                $table->id();

                $table->foreignId('brand_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->foreignId('market_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->foreignId('result_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->string('position', 20);
                $table->string('color', 20);
                $table->timestamps();

                $table->unique(
                    [
                        'brand_id',
                        'market_id',
                        'result_id',
                        'position',
                    ],
                    'paito_cell_color_unique',
                );

                $table->index(
                    [
                        'brand_id',
                        'market_id',
                        'result_id',
                    ],
                    'paito_cell_color_lookup',
                );
            },
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('paito_cell_colors');
    }
};
