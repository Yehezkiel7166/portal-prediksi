<?php

namespace App\Filament\Resources\ShioPeriods\Pages;

use App\Domains\Shio\Actions\GenerateShioBannerAction;
use App\Domains\Shio\Models\ShioPeriod;
use App\Filament\Resources\ShioPeriods\ShioPeriodResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Throwable;

class EditShioPeriod extends EditRecord
{
    protected static string $resource = ShioPeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generateBanner')
                ->label('Generate Banner')
                ->icon('heroicon-o-photo')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Generate Banner Shio')
                ->modalDescription(
                    'Banner lama akan diganti menggunakan template dan data Shio terbaru.'
                )
                ->modalSubmitActionLabel('Generate')
                ->disabled(
                    fn (): bool => blank(
                        $this->getRecord()->banner_template
                    )
                )
                ->action(function (): void {
                    /** @var ShioPeriod $period */
                    $period = $this->getRecord();

                    try {
                        app(GenerateShioBannerAction::class)
                            ->execute($period);

                        $this->refreshFormData([
                            'generated_banner',
                        ]);

                        Notification::make()
                            ->title('Banner Shio berhasil dibuat')
                            ->success()
                            ->send();
                    } catch (Throwable $exception) {
                        report($exception);

                        Notification::make()
                            ->title('Banner Shio gagal dibuat')
                            ->body($exception->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
