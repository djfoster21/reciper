<?php

namespace App\Observers;

use App\Models\IngredientCategory;

class IngredientCategoryObserver
{
    /**
     * Handle the IngredientCategory "created" event.
     */
    public function creating(IngredientCategory $ingredientCategory): void
    {
        $ingredientCategory->account()->associate(auth()->user()->account);
    }

    /**
     * Handle the IngredientCategory "updated" event.
     */
    public function updated(IngredientCategory $ingredientCategory): void
    {
        //
    }

    /**
     * Handle the IngredientCategory "deleted" event.
     */
    public function deleted(IngredientCategory $ingredientCategory): void
    {
        //
    }

    /**
     * Handle the IngredientCategory "restored" event.
     */
    public function restored(IngredientCategory $ingredientCategory): void
    {
        //
    }

    /**
     * Handle the IngredientCategory "force deleted" event.
     */
    public function forceDeleted(IngredientCategory $ingredientCategory): void
    {
        //
    }
}
