<?php

namespace App\Services;

use Brick\Math\RoundingMode;
use Brick\Money\AbstractMoney;
use Brick\Money\Context\CustomContext;
use Brick\Money\Money;
use Brick\Money\RationalMoney;

class PriceService
{
    public static function normalize(?AbstractMoney $price, int $decimals = 2): ?Money
    {
        return $price?->to(new CustomContext($decimals), RoundingMode::HALF_UP);
    }

    public static function toString(?AbstractMoney $price, int $decimals = 2): ?string
    {
        $normalizedPrice = self::normalize($price, $decimals);

        return $normalizedPrice?->getCurrency()->getCurrencyCode().' '.$normalizedPrice?->getAmount();
    }

    public static function toFloat(?AbstractMoney $price, int $decimal = 2): ?float
    {
        return self::normalize($price, $decimal)?->getAmount()->toFloat();
    }

    public static function fromFloat(?float $price, string $currency = 'EUR'): ?RationalMoney
    {
        return RationalMoney::of($price, $currency);
    }
}
