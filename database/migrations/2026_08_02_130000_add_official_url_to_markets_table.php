<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (
            Schema::hasTable('markets')
            && !Schema::hasColumn('markets', 'official_url')
        ) {
            Schema::table(
                'markets',
                function (Blueprint $table): void {
                    $table
                        ->string('official_url', 2048)
                        ->nullable()
                        ->after('slug');
                }
            );
        }
    }

    public function down(): void
    {
        if (
            Schema::hasTable('markets')
            && Schema::hasColumn('markets', 'official_url')
        ) {
            Schema::table(
                'markets',
                function (Blueprint $table): void {
                    $table->dropColumn('official_url');
                }
            );
        }
    }
};
