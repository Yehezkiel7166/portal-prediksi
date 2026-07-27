<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('results', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('market_id')
                ->constrained('markets')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->date('result_date');
            $table->text('winning_numbers');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(
                ['market_id', 'result_date'],
                'results_market_date_unique'
            );

            $table->index(
                ['result_date', 'market_id'],
                'results_date_market_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
