<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('markets', function (Blueprint $table): void {
            $table->json('active_days')->nullable()->after('timezone');
            $table->time('open_time')->nullable()->after('active_days');
            $table->time('close_time')->nullable()->after('open_time');
            $table->time('result_time')->nullable()->after('close_time');
            $table->boolean('is_holiday')->default(false)->after('result_time')->index();
            $table->string('holiday_note', 255)->nullable()->after('is_holiday');
        });
    }

    public function down(): void
    {
        Schema::table('markets', function (Blueprint $table): void {
            $table->dropIndex(['is_holiday']);
            $table->dropColumn([
                'active_days',
                'open_time',
                'close_time',
                'result_time',
                'is_holiday',
                'holiday_note',
            ]);
        });
    }
};
