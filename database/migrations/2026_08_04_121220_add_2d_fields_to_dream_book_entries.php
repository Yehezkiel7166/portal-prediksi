<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'dream_book_entries',
            function (Blueprint $table): void {
                $table->string('category', 20)
                    ->default('2D')
                    ->after('title');

                $table->text('description')
                    ->nullable()
                    ->after('category');

                $table->string('numbers', 255)
                    ->nullable()
                    ->after('description');
            },
        );

        DB::table('dream_book_entries')
            ->orderBy('id')
            ->eachById(
                function (object $entry): void {
                    DB::table('dream_book_entries')
                        ->where('id', $entry->id)
                        ->update([
                            'category' => '2D',
                            'description' => $entry->title,
                            'numbers' => $entry->number,
                        ]);
                },
            );
    }

    public function down(): void
    {
        Schema::table(
            'dream_book_entries',
            function (Blueprint $table): void {
                $table->dropColumn([
                    'category',
                    'description',
                    'numbers',
                ]);
            },
        );
    }
};
