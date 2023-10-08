<?php

namespace App\Observers;

use App\Models\RecipeCategory;

class RecipeCategoryObserver
{
    /**
     * Handle the RecipeCategory "created" event.
     */
    public function creating(RecipeCategory $recipeCategory): void
    {
        $recipeCategory->account()->associate(auth()->user()->account);
    }

    /**
     * Handle the RecipeCategory "updated" event.
     */
    public function updated(RecipeCategory $recipeCategory): void
    {
        //
    }

    /**
     * Handle the RecipeCategory "deleted" event.
     */
    public function deleted(RecipeCategory $recipeCategory): void
    {
        //
    }

    /**
     * Handle the RecipeCategory "restored" event.
     */
    public function restored(RecipeCategory $recipeCategory): void
    {
        //
    }

    /**
     * Handle the RecipeCategory "force deleted" event.
     */
    public function forceDeleted(RecipeCategory $recipeCategory): void
    {
        //
    }
}
