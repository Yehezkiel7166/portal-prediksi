<?php

namespace App\Filament\Resources\Results\Pages;

use App\Domains\Result\Actions\BulkImportResultsAction;
use App\Filament\Resources\Results\ResultResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Throwable;

class ListResults extends ListRecords
{
    protected static string $resource = ResultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('importResults')
                ->label('Import CSV/XLSX')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('gray')
                ->schema([
                    FileUpload::make('file')
                        ->label('File Result')
                        ->disk('local')
                        ->directory('result-imports')
                        ->acceptedFileTypes([
                            'text/csv',
                            'text/plain',
                            'application/csv',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        ])
                        ->maxSize(10240)
                        ->required()
                        ->helperText(
                            'Header wajib: market_code, result_date, winning_numbers. Header notes opsional.'
                        ),
                ])
                ->action(function (array $data): void {
                    $relativePath = (string) $data['file'];

                    $absolutePath = Storage::disk('local')
                        ->path($relativePath);

                    try {
                        $report = app(
                            BulkImportResultsAction::class
                        )->execute($absolutePath);

                        Notification::make()
                            ->success()
                            ->title('Import result berhasil')
                            ->body(sprintf(
                                '%d baris diproses: %d dibuat dan %d diperbarui.',
                                $report['total'],
                                $report['created'],
                                $report['updated'],
                            ))
                            ->send();
                    } catch (
                        ValidationException $exception
                    ) {
                        $messages = [];

                        foreach (
                            $exception->errors() as $errors
                        ) {
                            foreach ($errors as $error) {
                                $messages[] = $error;
                            }
                        }

                        Notification::make()
                            ->danger()
                            ->title('Import result gagal')
                            ->body(implode(
                                "\n",
                                array_slice(
                                    $messages,
                                    0,
                                    10,
                                ),
                            ))
                            ->persistent()
                            ->send();
                    } catch (Throwable $exception) {
                        report($exception);

                        Notification::make()
                            ->danger()
                            ->title('Import result gagal')
                            ->body(
                                'Terjadi kesalahan saat membaca file.'
                            )
                            ->persistent()
                            ->send();
                    } finally {
                        Storage::disk('local')->delete(
                            $relativePath
                        );
                    }
                }),

            CreateAction::make(),
        ];
    }
}
