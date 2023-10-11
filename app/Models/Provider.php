<?php

namespace App\Models;

use App\Models\Traits\HasAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provider extends Model
{
    use HasAccount;

    protected $fillable = [
        'account_id',
        'name',
    ];

    public function ingredientPrices(): HasMany
    {
        return $this->hasMany(IngredientCost::class);
    }
}
