<?php

namespace App\Filament\Synthesizers;

use App\Services\PriceService;
use Brick\Money\AbstractMoney;
use Brick\Money\Money;
use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;

class MoneySynth extends Synth
{
    public static $key = 'money';

    public static function match($target)
    {
        return $target instanceof AbstractMoney;
    }

    public function dehydrate(AbstractMoney $target, $dehydrateChild)
    {
        return [PriceService::toFloat($target), []];
    }

    public function hydrate($value, $meta, $hydrateChild): ?AbstractMoney
    {
        return PriceService::fromFloat($value, 'EUR');
    }

    public function set(&$target, $key, $value)
    {
        $target[$key] = $value;
    }

    public function unset(&$target, $key)
    {
        unset($target[$key]);
    }
}
