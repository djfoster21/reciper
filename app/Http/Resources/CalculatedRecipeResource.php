<?php

namespace App\Http\Resources;

use App\Services\PriceService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CalculatedRecipeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'recipe' => $this['recipe'],
            'totalCost' => PriceService::toString($this['totalCost']),
            'unitCost' => PriceService::toString($this['unitCost']),
            'salePrice' => PriceService::toString($this['salePrice']),
            'yield' => $this['yield'],
            'ingredients' => CalculatedRecipeIngredientResource::collection($this['ingredientCosts']),

            'oldValues' => parent::toArray($request),
        ];
    }
}
