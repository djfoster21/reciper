<?php

namespace App\Filament\Resources\IngredientResource\Pages;

use App\Filament\Resources\IngredientResource;
use App\Services\Importers\ImportCostsService;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListIngredients extends ListRecords
{
    protected static string $resource = IngredientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('import')
                ->label('Import Costs')
                ->icon('heroicon-o-arrow-up-tray')
                ->outlined()
                ->color('info')
                ->form([
                    FileUpload::make('file')
                        ->acceptedFileTypes(['application/excel', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                        ->required()
                        ->storeFiles(false),
                ])
                ->action(function (array $data, ImportCostsService $importCostsService) {
                    $importCostsService->handle($data['file']->getRealPath(), auth()->user()->account);

                    Notification::make()
                        ->success()
                        ->title('Costs imported')
                        ->body('Costs imported')
                        ->send();
                }),
            Actions\CreateAction::make(),
        ];
    }
}
