<?php

use Cknow\Money\Money;

dataset('read_ingredient_costs_data', static function () {
    return [collect([
        collect([
            'name' => 'ingredient A',
            'cost_provider' => 'provider A',
            'cost_unit_type' => 'unit type A',
            'cost_quantity' => 1,
            'cost_price' => Money::EUR(100),
        ]),
    ])];
});
