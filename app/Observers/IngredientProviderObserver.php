<?php

namespace App\Observers;

use App\Models\IngredientProvider;

class IngredientProviderObserver
{
    /**
     * Handle the IngredientProvider "created" event.
     */
    public function creating(IngredientProvider $ingredientProvider): void
    {
        $ingredientProvider->account()->associate(auth()->user()->account);
    }

    /**
     * Handle the IngredientProvider "updated" event.
     */
    public function updated(IngredientProvider $ingredientProvider): void
    {
        //
    }

    /**
     * Handle the IngredientProvider "deleted" event.
     */
    public function deleted(IngredientProvider $ingredientProvider): void
    {
        //
    }

    /**
     * Handle the IngredientProvider "restored" event.
     */
    public function restored(IngredientProvider $ingredientProvider): void
    {
        //
    }

    /**
     * Handle the IngredientProvider "force deleted" event.
     */
    public function forceDeleted(IngredientProvider $ingredientProvider): void
    {
        //
    }
}
