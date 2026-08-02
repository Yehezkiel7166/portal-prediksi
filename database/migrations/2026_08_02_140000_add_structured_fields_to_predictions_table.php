<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('predictions', function (Blueprint $table): void {
            $table->text('bbfs')
                ->nullable()
                ->after('predicted_numbers');

            $table->text('colok_bebas')
                ->nullable()
                ->after('bbfs');

            $table->text('prediction_2d')
                ->nullable()
                ->after('colok_bebas');

            $table->text('prediction_3d')
                ->nullable()
                ->after('prediction_2d');

            $table->text('prediction_4d')
                ->nullable()
                ->after('prediction_3d');

            $table->text('kembar')
                ->nullable()
                ->after('prediction_4d');

            $table->string('shio', 100)
                ->nullable()
                ->after('kembar');
        });
    }

    public function down(): void
    {
        Schema::table('predictions', function (Blueprint $table): void {
            $table->dropColumn([
                'bbfs',
                'colok_bebas',
                'prediction_2d',
                'prediction_3d',
                'prediction_4d',
                'kembar',
                'shio',
            ]);
        });
    }
};
