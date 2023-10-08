<?php

namespace App\Filament\Widgets;

use App\Models\Ingredient;
use App\Models\Recipe;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RecipeCountOverview extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = '1';

    protected function getStats(): array
    {
        return [
            Stat::make('Recipes', Recipe::all()->count()),
            Stat::make('Ingredients', Ingredient::all()->count()),
        ];
    }
}
