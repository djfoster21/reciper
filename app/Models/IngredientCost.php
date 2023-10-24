<?php

namespace App\Models;

use App\Models\Scopes\CurrentScope;
use App\Models\Traits\HasAccount;
use Brick\Money\Context\AutoContext;
use Brick\Money\Money;
use Brick\Money\RationalMoney;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Money $price
 */
class IngredientCost extends Model
{
    use HasAccount;

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

    public function scopeCurrent(Builder $query): void
    {
        (new CurrentScope())->apply($query, $this);
    }

    public function price(): Attribute
    {
        return Attribute::make(
            get: static fn ($value) => Money::of($value, 'EUR', new AutoContext()),
            set: static fn ($value) => (string) $value->getAmount(),
        );
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

    public function getPricePerUnitAttribute(): RationalMoney
    {
        if ($this->quantity === 0) {
            return $this->price->toRational();
        }

        return $this->price->toRational()->dividedBy($this->quantity);
    }
}
