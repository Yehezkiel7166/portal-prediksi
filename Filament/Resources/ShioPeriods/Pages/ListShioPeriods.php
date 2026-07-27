<?php

namespace App\Filament\Resources\ShioPeriods\Pages;

use App\Domains\Shio\Actions\GenerateShioBannerAction;
use App\Domains\Shio\Models\ShioPeriod;
use App\Filament\Resources\ShioPeriods\ShioPeriodResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Throwable;

class ListShioPeriods extends ListRecords
{
    protected static string $resource = ShioPeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),

            Action::make('generateBanner')
                ->label('Generate Banner')
                ->icon('heroicon-o-photo')
                ->color('primary')
                ->requiresConfirmation()
                ->action(function (): void {

                    $period = ShioPeriod::query()
                        ->latest('year')
                        ->first();

                    if (! $period) {
                        Notification::make()
                            ->title('Belum ada periode Shio')
                            ->body('Silakan buat periode Shio terlebih dahulu.')
                            ->warning()
                            ->send();

                        return;
                    }

                    if (blank($period->banner_template)) {
                        Notification::make()
                            ->title('Template banner belum tersedia')
                            ->body('Upload template banner sebelum melakukan generate.')
                            ->warning()
                            ->send();

                        return;
                    }

                    try {
                        app(GenerateShioBannerAction::class)
                            ->execute($period);

                        Notification::make()
                            ->title('Banner berhasil dibuat')
                            ->success()
                            ->send();

                    } catch (Throwable $e) {

                        report($e);

                        Notification::make()
                            ->title('Generate banner gagal')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
