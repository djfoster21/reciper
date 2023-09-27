<?php

namespace App\Services;

use App\Models\Recipe;
use App\Models\RecipeIngredient;
use Cknow\Money\Money;
use Illuminate\Support\Collection;

class RecipeCostCalculatorService
{
    public function getAllCosts(): Collection
    {
        return Recipe::all()->map(function (Recipe $recipe) {

            try {
                $result = $this->getCostByRecipe($recipe);
                $recipeCost = $result['totalCost'] * 1.2;
                $ingredientsCosts = $result['ingredientCosts'];
                $unitCost = $recipeCost / $recipe->yield;

                return [
                    'recipe' => $recipe->name,
                    'totalCost' => Money::EUR($recipeCost)->format(),
                    'unitCost' => Money::EUR($unitCost)->format(),
                    'salePrice' => Money::EUR($unitCost * 2),
                    'yield' => $recipe->yield,
                    'ingredientCosts' => $ingredientsCosts,
                ];
            } catch (\Exception $exception) {
                return null;
            }
        })->filter()->values();
    }

    public function getCostByRecipe(Recipe $recipe): array
    {
        $totalCost = 0;
        $ingredientCosts = $recipe
            ->ingredients
            ->map(function (RecipeIngredient $recipeIngredient) use (&$totalCost) {
                $ingredient = $recipeIngredient->ingredient;
                if ($ingredient->cost === null) {
                    throw new \Exception('Ingredient has no cost');
                }
                $recipeIngredient->quantity;
                if ($ingredient->cost->quantity !== 0) {
                    $costPerUnit = $ingredient->cost->price / $ingredient->cost->quantity;
                    $cost = $costPerUnit * $recipeIngredient->quantity;

                    $totalCost += $cost;
                    //dd($ingredient->name, $cost, $costPerUnit);
                    return [
                        'ingredient' => $ingredient->name,
                        'quantity' => $recipeIngredient->quantity,
                        'unit' => $ingredient->measurementType->name,
                        'cost' => Money::EUR($cost)->format(),
                        'costPerUnit' => Money::EUR($costPerUnit)->format(),
                    ];

                }

                return null;

            })->filter()->values();
        /*        $cost = $recipe->ingredients->reduce(function (Money $carry, RecipeIngredient $recipeIngredient) {
                    $ingredient = $recipeIngredient->ingredient;
                    if ($ingredient->cost === null) {
                        throw new \Exception('Ingredient has no cost');
                    }
                    $recipeIngredient->quantity;
                    if ($ingredient->cost->quantity !== 0) {
                        $costPerUnit = $ingredient->cost->price->divide($ingredient->cost->quantity);

                        return $carry->add($costPerUnit->multiply($recipeIngredient->quantity));
                    }

                    return $carry;

                }, Money::EUR(0));*/

        return [
            'ingredientCosts' => $ingredientCosts,
            'totalCost' => $totalCost,
        ];
    }
}
