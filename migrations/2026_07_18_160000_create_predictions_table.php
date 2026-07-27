<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('predictions', function (Blueprint $table): void {
            $table->id();
            $table->string('market', 100);
            $table->date('prediction_date');
            $table->text('predicted_numbers');
            $table->string('status', 20)->default('draft')->index();
            $table->text('notes')->nullable();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();

            $table->unique(
                ['market', 'prediction_date'],
                'predictions_market_date_unique'
            );

            $table->index(
                ['prediction_date', 'status'],
                'predictions_date_status_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('predictions');
    }
};
