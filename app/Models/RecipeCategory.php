<?php

namespace App\Models;

use App\Models\Traits\HasAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecipeCategory extends Model
{
    use HasAccount;

    protected $fillable = [
        'name',
        'account_id',
    ];

    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class);
    }
}
