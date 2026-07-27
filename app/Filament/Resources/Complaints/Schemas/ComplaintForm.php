<?php

namespace App\Filament\Resources\Complaints\Schemas;

use App\Domains\Complaint\Models\Complaint;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class ComplaintForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Data Keluhan')->schema([
                TextInput::make('reference_code')->label('Nomor referensi')->disabled(),
                TextInput::make('name')->label('Nama')->disabled(),
                TextInput::make('contact')->label('Kontak')->disabled(),
                TextInput::make('subject')->label('Subjek')->disabled()->columnSpanFull(),
                Textarea::make('message')->label('Isi keluhan')->disabled()->rows(8)->columnSpanFull(),
            ])->columns(2),
            Section::make('Penanganan')->schema([
                Select::make('status')->options([
                    Complaint::STATUS_OPEN => 'Terbuka',
                    Complaint::STATUS_REVIEWED => 'Ditinjau',
                    Complaint::STATUS_RESOLVED => 'Selesai',
                    Complaint::STATUS_REJECTED => 'Ditolak',
                ])->required(),
                Textarea::make('admin_notes')->label('Catatan admin')->rows(5)->columnSpanFull(),
            ]),
        ]);
    }
}
