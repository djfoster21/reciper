<?php

namespace App\Providers;

use App\Models\Ingredient;
use App\Models\IngredientCategory;
use App\Models\IngredientCost;
use App\Models\IngredientProvider;
use App\Models\MeasurementType;
use App\Models\Provider;
use App\Models\Recipe;
use App\Models\RecipeCategory;
use App\Models\RecipeIngredient;
use App\Observers\IngredientCategoryObserver;
use App\Observers\IngredientCostObserver;
use App\Observers\IngredientObserver;
use App\Observers\IngredientProviderObserver;
use App\Observers\MeasurementTypeObserver;
use App\Observers\ProviderObserver;
use App\Observers\RecipeCategoryObserver;
use App\Observers\RecipeIngredientObserver;
use App\Observers\RecipeObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    protected $observers = [
        Ingredient::class => [IngredientObserver::class],
        IngredientCategory::class => [IngredientCategoryObserver::class],
        IngredientCost::class => [IngredientCostObserver::class],
        IngredientProvider::class => [IngredientProviderObserver::class],
        MeasurementType::class => [MeasurementTypeObserver::class],
        Provider::class => [ProviderObserver::class],
        Recipe::class => [RecipeObserver::class],
        RecipeCategory::class => [RecipeCategoryObserver::class],
        RecipeIngredient::class => [RecipeIngredientObserver::class],

    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return true;
    }
}
