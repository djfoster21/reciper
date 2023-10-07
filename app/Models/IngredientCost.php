<?php

namespace App\Models;

use Brick\Money\Context\AutoContext;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Money $price
 */
class IngredientCost extends Model
{
    protected $fillable = [
        'account_id',
        'ingredient_id',
        'measurement_type_id',
        'provider_id',
        'quantity',
        'price',
        'valid_from',
        'valid_to',
    ];

    protected array $dates = [
        'valid_from',
        'valid_to',
    ];

    public function price(): Attribute
    {
        return Attribute::make(
            get: static fn ($value) => Money::of($value, 'EUR', new AutoContext()),
            set: static fn ($value) => (string) $value->getAmount(),
        );
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function measurementType(): BelongsTo
    {
        return $this->belongsTo(MeasurementType::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }
}
