<?php

namespace App\Models;

use App\Models\Traits\HasAccount;
use Brick\Money\RationalMoney;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecipeIngredient extends Model
{
    use HasAccount;

    protected $fillable = [
        'recipe_id',
        'ingredient_id',
        'account_id',
        'quantity',
        'unit',
    ];

    protected $appends = [
        'cost',
    ];

    protected $with = [
        'ingredient',
        'ingredient.cost',
    ];

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function getCostAttribute(): ?RationalMoney
    {
        return $this->ingredient?->cost?->pricePerUnit->multipliedBy($this->quantity);
    }
}
