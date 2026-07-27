<?php

namespace App\Filament\Resources\JackpotProofs\Schemas;

use App\Domains\JackpotProof\Models\JackpotProof;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class JackpotProofForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Konten')->schema([
                TextInput::make('title')->label('Judul')->required()->maxLength(255),
                TextInput::make('slug')->helperText('Kosongkan untuk membuat slug otomatis.')->maxLength(255),
                Textarea::make('description')->label('Deskripsi')->rows(4)->columnSpanFull(),
            ])->columns(2),
            Section::make('Bukti Gambar')->description('Upload wajib. Publikasi tetap memerlukan status Disetujui.')->schema([
                FileUpload::make('image_path')->label('Gambar bukti')->image()->disk('public')->directory('jackpot-proofs')->visibility('public')->imageEditor()->required()->columnSpanFull(),
                FileUpload::make('thumbnail_path')->label('Thumbnail opsional')->image()->disk('public')->directory('jackpot-proofs/thumbnails')->visibility('public')->columnSpanFull(),
            ]),
            Section::make('Moderasi dan Publikasi')->schema([
                Select::make('status')->options([
                    JackpotProof::STATUS_DRAFT => 'Draft',
                    JackpotProof::STATUS_PENDING => 'Menunggu Moderasi',
                    JackpotProof::STATUS_APPROVED => 'Disetujui',
                    JackpotProof::STATUS_REJECTED => 'Ditolak',
                ])->default(JackpotProof::STATUS_DRAFT)->required()->live(),
                DateTimePicker::make('published_at')->label('Tanggal publikasi')->seconds(false),
                TextInput::make('sort_order')->label('Urutan')->numeric()->minValue(0)->default(0)->required(),
                Textarea::make('moderation_notes')->label('Catatan moderasi')->rows(3)->columnSpanFull(),
            ])->columns(3),
            Section::make('SEO')->schema([
                TextInput::make('seo_title')->label('SEO title')->maxLength(255),
                Textarea::make('seo_description')->label('SEO description')->rows(3)->columnSpanFull(),
            ])->columns(2),
        ]);
    }
}
