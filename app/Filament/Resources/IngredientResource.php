<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IngredientResource\Pages;
use App\Models\Ingredient;
use App\Services\PriceService;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class IngredientResource extends Resource
{
    protected static ?string $model = Ingredient::class;

    protected static ?string $navigationGroup = 'Recipes';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('measurement_type_id')
                    ->relationship('measurementType', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('ingredient_category_id')
                    ->relationship('category', 'name'),
                Repeater::make('costs')
                    ->label('Costs')
                    ->addActionLabel('Add Cost')
                    ->relationship('costs')
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Select::make('measurement_type_id')
                            ->relationship('measurementType', 'name')
                            ->required(),
                        Forms\Components\Select::make('provider_id')
                            ->relationship('provider', 'name')
                            ->createOptionForm(null)
                            ->required(),
                        Forms\Components\TextInput::make('quantity')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->prefix('EUR'),
                        Forms\Components\DatePicker::make('valid_from'),
                        Forms\Components\DatePicker::make('valid_to'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('measurementType.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost.pricePerUnit')
                    ->label('Cost per unit')
                    ->state(fn (Ingredient $ingredient) => PriceService::toFloat($ingredient->cost?->price_per_unit))
                    ->money('EUR'),
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
            'index' => Pages\ListIngredients::route('/'),
            'create' => Pages\CreateIngredient::route('/create'),
            'edit' => Pages\EditIngredient::route('/{record}/edit'),
        ];
    }
}
