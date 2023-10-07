<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CalculatedRecipeResource;
use App\Services\RecipeCostCalculatorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CostController extends Controller
{
    public function __construct(private readonly RecipeCostCalculatorService $calculatorService)
    {
    }

    public function getCostByRecipe(Request $request): JsonResponse
    {
        $recipe = $request->validate([
            'recipe' => 'required|integer|exists:recipes,id',
        ]);

        $recipe = $recipe['recipe'];

        $cost = $this->calculatorService->getCostByRecipe($recipe);

        return response()->json([
            'cost' => $cost->getAmount(),
            'currency' => $cost->getCurrency()->getCode(),
        ]);

    }

    public function getAllCosts(): JsonResponse
    {
        $recipes = $this->calculatorService->getAllCosts();

        return response()->json([
            'recipes' => CalculatedRecipeResource::collection($recipes),
        ]);
    }
}
