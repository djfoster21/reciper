<?php

namespace App\Services\Importers;

use App\Models\Account;
use App\Models\Ingredient;
use App\Models\IngredientCost;
use App\Models\MeasurementType;
use App\Models\Provider;
use App\Services\Readers\TemplateExcelCostReader;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ImportCostsService implements ImportServiceInterface
{
    public function handle(string $file, Account $account): Collection
    {
        $accountId = $account->id;
        DB::beginTransaction();
        $readCosts = (new TemplateExcelCostReader())->handle($file);

        $providers = Provider::all();
        $measurementTypes = MeasurementType::all();
        $ingredients = Ingredient::all();
        $ingredientCosts = IngredientCost::all();

        $readCosts->each(function (Collection $cost) use (
            $accountId,
            &$providers,
            &$measurementTypes,
            &$ingredients,
            &$ingredientCosts
        ) {
            $provider = $providers->firstWhere('name', $cost['cost_provider']);
            if (! $provider) {
                $provider = Provider::create([
                    'name' => $cost['cost_provider'],
                    'account_id' => $accountId,
                ]);
                $providers = $providers->push($provider);
            }

            $measurementType = $measurementTypes->firstWhere('name', $cost['cost_unit_type']);
            if (! $measurementType) {
                $measurementType = MeasurementType::create([
                    'name' => $cost['cost_unit_type'],
                    'account_id' => $accountId,
                ]);
                $measurementTypes = $measurementTypes->push($measurementType);
            }

            $ingredient = $ingredients->firstWhere('name', $cost['name']);
            if (! $ingredient) {
                $ingredient = Ingredient::create([
                    'name' => $cost['name'],
                    'account_id' => $accountId,
                    'measurement_type_id' => $measurementType->id,
                ]);
                $ingredients = $ingredients->push($ingredient);
            }
            $ingredientCost = $ingredientCosts->firstWhere('ingredient_id', $ingredient->id);
            if (! $ingredientCost) {
                $ingredientCost = IngredientCost::create([
                    'ingredient_id' => $ingredient->id,
                    'account_id' => $accountId,
                    'provider_id' => $provider->id,
                    'quantity' => $cost['cost_quantity'],
                    'measurement_type_id' => $measurementType->id,
                    'price' => $cost['cost_price'],
                ]);
                $ingredientCosts = $ingredientCosts->push($ingredientCost);
            }
        });
        DB::commit();
        $createdProviders = $providers->filter(fn ($provider) => $provider->wasRecentlyCreated)->count();
        $createdMeasurementTypes = $measurementTypes->filter(fn ($measurementType) => $measurementType->wasRecentlyCreated)->count();
        $createdIngredients = $ingredients->filter(fn ($ingredient) => $ingredient->wasRecentlyCreated)->count();
        $createdIngredientCosts = $ingredientCosts->filter(fn ($ingredientCost) => $ingredientCost->wasRecentlyCreated)->count();

        $result = [
            'ingredientCost' => [
                'created' => $createdIngredientCosts,
                'updated' => $ingredientCosts->count() - $createdIngredientCosts,
            ],
            'ingredient' => [
                'created' => $createdIngredients,
                'updated' => $ingredients->count() - $createdIngredients,
            ],
            'measurementType' => [
                'created' => $createdMeasurementTypes,
                'updated' => $measurementTypes->count() - $createdMeasurementTypes,
            ],
            'provider' => [
                'created' => $createdProviders,
                'updated' => $providers->count() - $createdProviders,
            ],
        ];

        return collect($result);
    }
}
