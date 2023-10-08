<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IngredientCostResource\Pages;
use App\Models\IngredientCost;
use App\Services\PriceService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;

class IngredientCostResource extends Resource
{
    protected static ?string $model = IngredientCost::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Recipes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('account_id')
                    ->relationship('account', 'name')
                    ->required(),
                Forms\Components\Select::make('ingredient_id')
                    ->relationship('ingredient', 'name')
                    ->required(),
                Forms\Components\Select::make('measurement_type_id')
                    ->relationship('measurementType', 'name')
                    ->required(),
                Forms\Components\Select::make('provider_id')
                    ->relationship('provider', 'name')
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->prefix('EUR'),
                Forms\Components\DatePicker::make('valid_from'),
                Forms\Components\DatePicker::make('valid_to'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ingredient.name')
                    ->numeric()
                    ->weight(FontWeight::Bold)
                    ->sortable(),
                Tables\Columns\TextColumn::make('provider.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->suffix(fn (IngredientCost $ingredientCost) => ' '.$ingredientCost->measurementType->name)
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->state(function (IngredientCost $ingredientCost) {
                        return PriceService::toFloat($ingredientCost->price);
                    })
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('valid_from')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('valid_to')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIngredientCosts::route('/'),
            'create' => Pages\CreateIngredientCost::route('/create'),
            'edit' => Pages\EditIngredientCost::route('/{record}/edit'),
        ];
    }
}
