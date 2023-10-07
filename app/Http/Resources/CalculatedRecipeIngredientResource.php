<?php

namespace App\Http\Resources;

use App\Services\PriceService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CalculatedRecipeIngredientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'ingredient' => $this['ingredient'],
            'amount' => $this['amount'],
            'unit' => $this['unit'],
            'cost' => PriceService::toString($this['cost']),
            'unitCost' => PriceService::toString($this['unitCost']),
        ];

        return parent::toArray($request);
    }
}
