<?php

namespace App\Models;

use App\Models\Traits\HasAccount;
use Brick\Money\RationalMoney;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recipe extends Model
{
    use HasAccount;

    protected $fillable = [
        'name',
        'account_id',
        'yield',
        'notes',
    ];

    protected $appends = [
        'cost',
        'costPerUnit',
    ];

    protected $with = [
        'category',
        'ingredients',
        'ingredients.ingredient',
        'ingredients.ingredient.cost',
        'ingredients.ingredient.measurementType',
    ];

    public function ingredients(): HasMany
    {
        return $this->hasMany(RecipeIngredient::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(RecipeCategory::class, 'recipe_category_id');
    }

    public function getCostAttribute(): RationalMoney
    {
        return $this->ingredients
            ->pluck('cost')
            ->reduce(function (RationalMoney $carry, $cost) {
                if ($cost) {
                    return $carry->plus($cost);
                }

                return $carry;

            }, RationalMoney::of(0, 'EUR'));
    }

    public function getCostPerUnitAttribute(): RationalMoney
    {
        return $this->cost->dividedBy($this->yield);
    }
}
