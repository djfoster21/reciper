<?php

namespace App\Filament\Resources\RecipeResource\Pages;

use App\Filament\Resources\RecipeResource;
use App\Services\Importers\ImportRecipeService;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ListRecipes extends ListRecords
{
    protected static string $resource = RecipeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('import')
                ->label('Import Recipes')
                ->icon('heroicon-o-arrow-up-tray')
                ->outlined()
                ->color('info')
                ->form([
                    FileUpload::make('file')
                        ->label('File')
                        ->acceptedFileTypes(['application/excel', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                        ->required()
                        ->storeFiles(false),
                ])
                ->action(function (array $data, ImportRecipeService $importRecipeService) {
                    /** @var TemporaryUploadedFile $file */
                    $file = $data['file'];

                    $result = $importRecipeService->handle($file->getRealPath(), auth()->user()->account);
                    $notificationBody = 'Recipes: '.
                        $result->get('recipe')['created'].' created | '.
                        $result->get('recipe')['updated'].' updated';

                    Notification::make()
                        ->success()
                        ->title('Recipes imported')
                        ->body($notificationBody)
                        ->send();
                }),
            Actions\CreateAction::make()
                ->label('Add Recipe')
                ->icon('heroicon-o-plus-circle')
                ->color('primary'),
        ];
    }
}
