<?php

namespace App\Filament\Resources\Complaints\Tables;

use App\Domains\Complaint\Models\Complaint;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class ComplaintsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference_code')->label('Referensi')->searchable()->copyable(),
                TextColumn::make('subject')->label('Subjek')->searchable()->limit(50),
                TextColumn::make('name')->label('Pengirim')->searchable(),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('created_at')->label('Dikirim')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    Complaint::STATUS_OPEN => 'Terbuka',
                    Complaint::STATUS_REVIEWED => 'Ditinjau',
                    Complaint::STATUS_RESOLVED => 'Selesai',
                    Complaint::STATUS_REJECTED => 'Ditolak',
                ]),
            ])
            ->defaultSort('id', 'desc')
            ->recordActions([EditAction::make()]);
    }
}
