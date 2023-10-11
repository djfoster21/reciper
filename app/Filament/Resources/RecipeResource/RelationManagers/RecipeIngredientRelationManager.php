<?php

namespace App\Filament\Resources\RecipeResource\RelationManagers;

use App\Filament\Tables\Components\Columns\Summarizers\SumMoney;
use App\Models\RecipeIngredient;
use App\Services\PriceService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RecipeIngredientRelationManager extends RelationManager
{
    protected static string $relationship = 'ingredients';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('ingredient')
                    ->relationship('ingredient', 'name')
                    ->required(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->suffix($form->getRecord()->ingredient->measurementType->name),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('ingredient')
            ->columns([
                TextColumn::make('ingredient.name'),
                TextColumn::make('quantity')
                    ->suffix(fn (RecipeIngredient $recipeIngredient) => ' '.$recipeIngredient->ingredient->measurementType->name)
                    ->alignStart(true),
                TextColumn::make('cost')
                    ->label(__('recipe.totalCost'))
                    ->state(fn (RecipeIngredient $recipeIngredient) => PriceService::toFloat($recipeIngredient->cost) ?? 0.00)
                    ->summarize(SumMoney::make()->money('EUR'))
                    ->money('EUR'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
