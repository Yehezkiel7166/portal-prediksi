<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->string('reference_code', 32)->unique();
            $table->string('name', 120);
            $table->string('contact', 190);
            $table->string('subject', 190);
            $table->text('message');
            $table->string('status', 24)->default('open');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('admin_notes')->nullable();
            $table->string('source_ip', 45)->nullable();
            $table->string('user_agent', 1000)->nullable();
            $table->timestamps();

            $table->index(['brand_id', 'status', 'created_at'], 'complaints_workflow_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
