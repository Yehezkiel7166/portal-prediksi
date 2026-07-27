<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rtp_snapshots', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->foreignId('brand_slot_id')->constrained('brand_slots')->cascadeOnDelete();
            $table->decimal('rtp_value', 5, 2);
            $table->timestamp('captured_at');
            $table->string('source_label', 120)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['brand_slot_id', 'captured_at']);
            $table->index(['brand_id', 'captured_at']);
        });
    }

    public function down(): void { Schema::dropIfExists('rtp_snapshots'); }
};
