<?php

namespace App\Models;

use App\Models\Traits\HasAccount;
use Illuminate\Database\Eloquent\Model;

class IngredientProvider extends Model
{
    use HasAccount;

    protected $fillable = [
        'name',
        'account_id',
    ];
}
