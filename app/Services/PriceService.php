<?php

namespace App\Services;

use Brick\Math\RoundingMode;
use Brick\Money\AbstractMoney;
use Brick\Money\Context\CustomContext;
use Brick\Money\Money;

class PriceService
{
    public static function normalize(AbstractMoney $price, int $decimals = 2): Money
    {
        return $price->to(new CustomContext($decimals), RoundingMode::HALF_UP);
    }

    public static function toString(AbstractMoney $price): string
    {
        $normalizedPrice = self::normalize($price);

        return $normalizedPrice->getCurrency()->getCurrencyCode().' '.$normalizedPrice->getAmount();
    }
}
