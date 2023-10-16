<?php

namespace App\Services\Importers;

use App\Enums\DefaultImportValuesEnum;
use App\Models\Account;
use App\Models\Ingredient;
use App\Models\IngredientCategory;
use App\Models\MeasurementType;
use App\Models\Recipe;
use App\Models\RecipeCategory;
use App\Models\RecipeIngredient;
use App\Services\Readers\TemplateExcelRecipeReader;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ImportRecipeService implements ImportServiceInterface
{
    public function handle(string $file, Account $account): Collection
    {
        $readRecipes = (new TemplateExcelRecipeReader())->handle($file);
        $accountId = $account->id;
        $recipes = Recipe::all();
        $recipeCategories = RecipeCategory::all();
        $recipeIngredients = RecipeIngredient::all();
        $ingredients = Ingredient::all();
        $ingredientsCategories = IngredientCategory::all();
        $measurementTypes = MeasurementType::all();

        DB::beginTransaction();
        $loadedRecipes = $readRecipes->map(function (Collection $readRecipe) use (
            $accountId,
            &$recipes,
            &$recipeCategories,
            &$recipeIngredients,
            &$ingredients,
            &$ingredientsCategories,
            &$measurementTypes
        ) {
            $currentRecipe = $recipes->firstWhere('name', $readRecipe->get('name'));
            if ($currentRecipe) {
                $currentRecipe->ingredients()->detach();
            } else {
                $currentRecipe = Recipe::create([
                    'name' => $readRecipe->get('name'),
                    'yield' => $readRecipe->get('yield'),
                    'notes' => $readRecipe->get('notes'),
                    'account_id' => $accountId,
                ]);
                $readRecipeCategory = $readRecipe->get('category',
                    DefaultImportValuesEnum::DEFAULT_RECIPE_CATEGORY) === '' ?: $readRecipe->get('category');
                $currentRecipeCategory = $recipeCategories->firstWhere('name', $readRecipeCategory);
                if (! $currentRecipeCategory) {
                    $currentRecipeCategory = RecipeCategory::create([
                        'name' => $readRecipeCategory,
                        'account_id' => $accountId,
                    ]);
                    $recipeCategories = $recipeCategories->push($currentRecipeCategory);
                }
                $currentRecipe = $currentRecipe->category()->associate($currentRecipeCategory);
                $currentRecipe->save();
                $recipes = $recipes->push($currentRecipe);
            }

            /** @var Collection $readIngredients */
            $readIngredients = $readRecipe->get('ingredients');
            $readIngredients = $readIngredients->each(function (Collection $readIngredient) use (
                $accountId,
                &$currentRecipe,
                &$recipeIngredients,
                &$ingredients,
                &$ingredientsCategories,
                &$measurementTypes
            ) {
                $currentIngredient = $ingredients->firstWhere('name', $readIngredient->get('name'));
                if (! $currentIngredient) {
                    $currentIngredient = new Ingredient([
                        'name' => $readIngredient->get('name'),
                        'account_id' => $accountId,
                    ]);

                    $readIngredientCategory = $readIngredient->get('category') === ''
                        ? DefaultImportValuesEnum::DEFAULT_INGREDIENT_CATEGORY : $readIngredient->get('category');
                    $currentIngredientCategory = $ingredientsCategories->firstWhere('name', $readIngredientCategory);
                    if (! $currentIngredientCategory) {
                        $currentIngredientCategory = new IngredientCategory([
                            'name' => $readIngredientCategory,
                            'account_id' => $accountId,
                        ]);
                        $currentIngredientCategory->save();
                        $ingredientsCategories = $ingredientsCategories->push($currentIngredientCategory);
                    }
                    /** @var Ingredient $currentIngredient */
                    $currentIngredient = $currentIngredient->category()->associate($currentIngredientCategory);

                    $currentMeasurementType = $measurementTypes->firstWhere('name', $readIngredient->get('unit_type'));
                    if (! $currentMeasurementType) {
                        $currentMeasurementType = new MeasurementType([
                            'name' => $readIngredient->get('unit_type'),
                            'account_id' => $accountId,
                        ]);
                        $currentMeasurementType->save();
                        $measurementTypes = $measurementTypes->push($currentMeasurementType);
                    }
                    $currentIngredient = $currentIngredient->measurementType()->associate($currentMeasurementType);
                    $currentIngredient->save();
                    $ingredients = $ingredients->push($currentIngredient);
                }
                $currentRecipeIngredient = $recipeIngredients
                    ->where('recipe_id', $currentRecipe->id)
                    ->where('ingredient_id', $currentIngredient->id)
                    ->first();
                if (! $currentRecipeIngredient) {

                    $currentRecipeIngredient = RecipeIngredient::create([
                        'recipe_id' => $currentRecipe->id,
                        'ingredient_id' => $currentIngredient->id,
                        'account_id' => $accountId,
                        'quantity' => $readIngredient->get('quantity'),
                    ]);
                }
                $recipeIngredients = $recipeIngredients->push($currentRecipeIngredient);
                $currentRecipe->save();
            });

            return $currentRecipe;
        });
        DB::commit();
        $createdRecipes = $recipes->filter(fn ($recipe) => $recipe->wasRecentlyCreated)->count();
        $createdRecipeCategories = $recipeCategories->filter(fn ($recipe) => $recipe->wasRecentlyCreated)->count();
        $createdRecipeIngredients = $recipeIngredients->filter(fn ($recipe) => $recipe->wasRecentlyCreated)->count();
        $createdIngredients = $ingredients->filter(fn ($recipe) => $recipe->wasRecentlyCreated)->count();
        $createdIngredientsCategories = $ingredientsCategories->filter(fn ($recipe) => $recipe->wasRecentlyCreated)->count();
        $createdMeasurementTypes = $measurementTypes->filter(fn ($recipe) => $recipe->wasRecentlyCreated)->count();

        $result = [
            'recipe' => [
                'created' => $createdRecipes,
                'updated' => $recipes->count() - $createdRecipes,
            ],
            'recipeCategory' => [
                'created' => $createdRecipeCategories,
                'updated' => $recipeCategories->count() - $createdRecipeCategories,
            ],
            'recipeIngredient' => [
                'created' => $createdRecipeIngredients,
                'updated' => $recipeIngredients->count() - $createdRecipeIngredients,
            ],
            'ingredient' => [
                'created' => $createdIngredients,
                'updated' => $ingredients->count() - $createdIngredients,
            ],
            'ingredientCategory' => [
                'created' => $createdIngredientsCategories,
                'updated' => $ingredientsCategories->count() - $createdIngredientsCategories,
            ],
            'measurementType' => [
                'created' => $createdMeasurementTypes,
                'updated' => $measurementTypes->count() - $createdMeasurementTypes,
            ],
        ];

        return collect($result);
    }
}
