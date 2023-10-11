<?php

namespace App\Filament\Resources\IngredientCostResource\Pages;

use App\Filament\Resources\IngredientCostResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIngredientCosts extends ListRecords
{
    protected static string $resource = IngredientCostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
