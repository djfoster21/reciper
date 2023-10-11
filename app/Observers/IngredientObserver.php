<?php

namespace App\Observers;

use App\Models\Ingredient;

class IngredientObserver
{
    /**
     * Handle the Ingredient "created" event.
     */
    public function creating(Ingredient $ingredient): void
    {
        $ingredient->account()->associate(auth()->user()->account);
    }

    /**
     * Handle the Ingredient "updated" event.
     */
    public function updated(Ingredient $ingredient): void
    {
        //
    }

    /**
     * Handle the Ingredient "deleted" event.
     */
    public function deleted(Ingredient $ingredient): void
    {
        //
    }

    /**
     * Handle the Ingredient "restored" event.
     */
    public function restored(Ingredient $ingredient): void
    {
        //
    }

    /**
     * Handle the Ingredient "force deleted" event.
     */
    public function forceDeleted(Ingredient $ingredient): void
    {
        //
    }
}
