<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecipeResource\Pages;
use App\Filament\Resources\RecipeResource\RelationManagers\RecipeIngredientRelationManager;
use App\Models\Recipe;
use App\Services\PriceService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RecipeResource extends Resource
{
    protected static ?string $model = Recipe::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Recipes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->autofocus()
                    ->required()
                    ->maxLength(255),
                TextInput::make('yield')
                    ->required()
                    ->numeric()
                    ->maxLength(255),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required()
                    ->createOptionForm(fn (Form $form) => RecipeCategoryResource::form($form)),
                Textarea::make('notes'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
/*            ->modifyQueryUsing(function (Builder $query) {
                return $query->with([
                    'category',
                    'ingredients',
                    'ingredients.ingredient',
                    'ingredients.ingredient.cost',
                    'ingredients.ingredient.measurementType',
                ]);
            })*/
            ->inverseRelationship('ingredients')
            ->columns([
                TextColumn::make('name')
                    ->weight(FontWeight::Bold)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('yield')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('costs')
                    ->label(__('recipe.totalCost'))
                    ->state(fn (Recipe $recipe) => PriceService::toFloat($recipe->cost) ?? 0.00)
                    ->money('EUR'),
                TextColumn::make('cost_per_unit')
                    ->label('Cost per unit')
                    ->state(fn (Recipe $recipe) => PriceService::toFloat($recipe->costPerUnit) ?? 0.00)
                    ->money('EUR'),
                TextColumn::make('ingredients_count')
                    ->label('Ingredients')
                    ->counts('ingredients')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RecipeIngredientRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecipes::route('/'),
            'create' => Pages\CreateRecipe::route('/create'),
            'edit' => Pages\EditRecipe::route('/{record}/edit'),
        ];
    }
}
