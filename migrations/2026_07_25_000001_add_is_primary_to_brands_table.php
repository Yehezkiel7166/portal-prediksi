<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('brands', 'is_primary')) {
            Schema::table('brands', function (Blueprint $table) {
                $table->boolean('is_primary')
                    ->default(false)
                    ->after('domain');

                $table->index([
                    'is_active',
                    'is_primary',
                ], 'brands_active_primary_idx');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('brands', 'is_primary')) {

            Schema::table('brands', function (Blueprint $table) {

                $table->dropIndex('brands_active_primary_idx');

                $table->dropColumn('is_primary');

            });

        }
    }
};
