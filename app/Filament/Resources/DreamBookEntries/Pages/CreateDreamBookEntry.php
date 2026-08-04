<?php

namespace App\Filament\Resources\DreamBookEntries\Pages;

use App\Domains\Brand\Support\BrandContext;
use App\Filament\Resources\DreamBookEntries\DreamBookEntryResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use RuntimeException;

class CreateDreamBookEntry extends CreateRecord
{
    protected static string $resource =
        DreamBookEntryResource::class;

    protected function mutateFormDataBeforeCreate(
        array $data,
    ): array {
        $brandId = app(BrandContext::class)
            ->get()
            ?->getKey();

        if ($brandId === null) {
            throw new RuntimeException(
                'Brand context tidak tersedia.'
            );
        }

        $description = trim(
            (string) ($data['description'] ?? '')
        );

        $number = trim(
            (string) ($data['number'] ?? '')
        );

        $data['brand_id'] = $brandId;
        $data['title'] = $description;
        $data['slug'] = Str::slug(
            'mimpi-'.$number
        );
        $data['interpretation'] = $description;
        $data['keywords'] = [];

        return $data;
    }
}
