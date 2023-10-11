<?php

namespace App\Observers;

use App\Models\RecipeIngredient;

class RecipeIngredientObserver
{
    /**
     * Handle the RecipeIngredient "created" event.
     */
    public function creating(RecipeIngredient $recipeIngredient): void
    {
        $recipeIngredient->account()->associate(auth()->user()->account);
    }

    /**
     * Handle the RecipeIngredient "updated" event.
     */
    public function updated(RecipeIngredient $recipeIngredient): void
    {
        //
    }

    /**
     * Handle the RecipeIngredient "deleted" event.
     */
    public function deleted(RecipeIngredient $recipeIngredient): void
    {
        //
    }

    /**
     * Handle the RecipeIngredient "restored" event.
     */
    public function restored(RecipeIngredient $recipeIngredient): void
    {
        //
    }

    /**
     * Handle the RecipeIngredient "force deleted" event.
     */
    public function forceDeleted(RecipeIngredient $recipeIngredient): void
    {
        //
    }
}
