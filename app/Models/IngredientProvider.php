<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngredientProvider extends Model
{
    protected $fillable = [
        'name',
        'account_id',
    ];
}
