<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('complaints', function (Blueprint $table): void {
            $table->text('admin_response')->nullable()->after('admin_notes');
            $table->timestamp('responded_at')->nullable()->after('admin_response');
        });

        DB::table('complaints')
            ->where('status', 'reviewed')
            ->update(['status' => 'in_progress']);

        Schema::create('complaint_status_histories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('complaint_id')->constrained()->cascadeOnDelete();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->string('from_status', 24)->nullable();
            $table->string('to_status', 24);
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('admin_response')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index(['brand_id', 'complaint_id', 'created_at'], 'complaint_history_lookup');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaint_status_histories');

        Schema::table('complaints', function (Blueprint $table): void {
            $table->dropColumn(['admin_response', 'responded_at']);
        });
    }
};
