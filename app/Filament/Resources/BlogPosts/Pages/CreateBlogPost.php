<?php

namespace App\Filament\Resources\BlogPosts\Pages;

use App\Domains\Blog\Actions\UpsertBlogPostAction;
use App\Filament\Resources\BlogPosts\BlogPostResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateBlogPost extends CreateRecord
{
    protected static string $resource = BlogPostResource::class;

    protected static bool $canCreateAnother = false;

    protected function handleRecordCreation(array $data): Model
    {
        return app(UpsertBlogPostAction::class)->execute($data);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Artikel blog berhasil dibuat';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
