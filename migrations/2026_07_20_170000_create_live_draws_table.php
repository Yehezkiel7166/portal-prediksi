<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('live_draws', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('market_id')
                ->constrained('markets')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('title');
            $table->string('slug')->unique();

            $table->string('provider')->default('official');
            $table->string('stream_type')->default('url');
            $table->text('source_url')->nullable();

            $table->json('draw_days')->nullable();
            $table->time('draw_time')->nullable();
            $table->string('timezone', 100)
                ->default('Asia/Jakarta');

            $table->string('status', 20)
                ->default('offline')
                ->index();

            $table->string('headline')->nullable();
            $table->text('footer')->nullable();

            $table->string('logo_path', 2048)->nullable();
            $table->string('background_path', 2048)->nullable();
            $table->string('background_focal_point')
                ->default('center');

            $table->unsignedInteger('priority')
                ->default(0)
                ->index();

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(
                ['market_id', 'status', 'priority'],
                'live_draws_market_status_priority_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('live_draws');
    }
};
