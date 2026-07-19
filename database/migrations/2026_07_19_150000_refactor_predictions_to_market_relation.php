<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('predictions', function (Blueprint $table): void {
            $table->foreignId('market_id')
                ->nullable()
                ->after('id')
                ->constrained('markets')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });

        Schema::table('predictions', function (Blueprint $table): void {
            $table->dropUnique('predictions_market_date_unique');

            $table->unique(
                ['market_id', 'prediction_date'],
                'predictions_market_date_unique'
            );
        });

        Schema::table('predictions', function (Blueprint $table): void {
            $table->dropColumn('market');
        });

        Schema::table('predictions', function (Blueprint $table): void {
            $table->unsignedBigInteger('market_id')
                ->nullable(false)
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('predictions', function (Blueprint $table): void {
            $table->string('market', 100)
                ->nullable()
                ->after('id');
        });

        Schema::table('predictions', function (Blueprint $table): void {
            $table->dropUnique('predictions_market_date_unique');
            $table->dropConstrainedForeignId('market_id');

            $table->unique(
                ['market', 'prediction_date'],
                'predictions_market_date_unique'
            );
        });

        Schema::table('predictions', function (Blueprint $table): void {
            $table->string('market', 100)
                ->nullable(false)
                ->change();
        });
    }
};
