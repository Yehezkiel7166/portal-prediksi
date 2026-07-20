<?php

namespace App\Filament\Resources\BlogPosts\Pages;

use App\Domains\Blog\Actions\UpsertBlogPostAction;
use App\Domains\Blog\Models\BlogPost;
use App\Filament\Resources\BlogPosts\BlogPostResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditBlogPost extends EditRecord
{
    protected static string $resource = BlogPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(
        Model $record,
        array $data,
    ): Model {
        /** @var BlogPost $record */

        return app(UpsertBlogPostAction::class)
            ->execute($data, $record);
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Artikel blog berhasil diperbarui';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
