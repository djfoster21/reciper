<?php

namespace App\Models;

use App\Models\Traits\HasAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MeasurementType extends Model
{
    use HasAccount;

    protected $fillable = [
        'name',
        'account_id',
    ];

    public function ingredients(): HasMany
    {
        return $this->hasMany(Ingredient::class);
    }
}
