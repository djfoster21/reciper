<?php

namespace App\Services;

use App\Exceptions\NoCostException;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use Brick\Money\Money;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class RecipeCostCalculatorService
{
    public const DEFAULT_WASTE_COST_MULTIPLIER = 1.2;

    public const DEFAULT_SALE_VALUE_MULTIPLIER = 2;

    public function getAllCosts(): Collection
    {
        return Recipe::all()->map(function (Recipe $recipe) {
            try {
                return $this->getCostByRecipe($recipe);
            } catch (Exception $exception) {
                Log::error('Error calculating cost', [
                    'recipe' => $recipe->name,
                    'exception' => $exception->getMessage(),
                ]);

                return null;
            }
        })->filter()->values();
    }

    public function getCostByRecipe(Recipe $recipe): array
    {
        $totalCost = Money::of(0, 'EUR')->toRational();
        $ingredientCosts = $recipe
            ->ingredients
            ->map(function (RecipeIngredient $recipeIngredient) use (&$totalCost) {
                $ingredient = $recipeIngredient->ingredient;
                if ($ingredient->cost === null) {
                    Log::error('Ingredient has no cost', [
                        'ingredient' => $ingredient->name,
                    ]);

                    //throw new NoCostException('Ingredient has no cost');
                    return null;
                }

                if ($ingredient->cost->quantity !== 0) {
                    $costPerUnit = $ingredient->cost->price->toRational()->dividedBy($ingredient->cost->quantity);
                    $cost = $costPerUnit->multipliedBy($recipeIngredient->quantity);
                    $totalCost = $totalCost->plus($cost);

                    return [
                        'ingredient' => $ingredient->name,
                        'amount' => $recipeIngredient->quantity,
                        'unit' => $ingredient->measurementType->name,
                        'cost' => $cost,
                        'unitCost' => $costPerUnit,
                    ];

                }

                return null;

            })->filter()->values();

        $recipeCost = $totalCost->multipliedBy(self::DEFAULT_WASTE_COST_MULTIPLIER);
        $unitCost = $recipeCost->dividedBy($recipe->yield);

        return [
            'ingredients' => $ingredientCosts,
            'totalCost' => $recipeCost,
            'recipe' => $recipe->name,
            'unitCost' => $unitCost,
            'salePrice' => $unitCost->multipliedBy(self::DEFAULT_SALE_VALUE_MULTIPLIER),
            'yield' => $recipe->yield,
            'error' => null,
        ];
    }
}
