<?php

namespace App\Observers;

use App\Models\IngredientCost;

class IngredientCostObserver
{
    /**
     * Handle the IngredientCost "created" event.
     */
    public function creating(IngredientCost $ingredientCost): void
    {
        $ingredientCost->account()->associate(auth()->user()->account);

        IngredientCost::where('ingredient_id', $ingredientCost->ingredient_id)
            ->whereNull('valid_to')
            ->each(function (IngredientCost $ingredientCost) {
                $ingredientCost->valid_to = now();
                $ingredientCost->save();

                return $ingredientCost;
            });
        $ingredientCost->valid_from = now();
        $ingredientCost->valid_to = null;
    }

    /**
     * Handle the IngredientCost "updated" event.
     */
    public function updated(IngredientCost $ingredientCost): void
    {
        //
    }

    /**
     * Handle the IngredientCost "deleted" event.
     */
    public function deleted(IngredientCost $ingredientCost): void
    {
        //
    }

    /**
     * Handle the IngredientCost "restored" event.
     */
    public function restored(IngredientCost $ingredientCost): void
    {
        //
    }

    /**
     * Handle the IngredientCost "force deleted" event.
     */
    public function forceDeleted(IngredientCost $ingredientCost): void
    {
        //
    }
}
