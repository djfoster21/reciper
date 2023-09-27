<?php

dataset('read_recipe_data_one_recipe', function () {
    return [collect([
        collect([
            'name' => 'recipe A',
            'yield' => 1,
            'category' => 'category A',
            'notes' => 'notes A',
            'ingredients' => collect([
                collect([
                    'name' => 'ingredient A',
                    'category' => 'category A',
                    'unit_type' => 'unit type A',
                    'quantity' => 1,
                ]),
                collect([
                    'name' => 'ingredient B',
                    'category' => 'category B',
                    'unit_type' => 'unit type B',
                    'quantity' => 2,
                ]),
            ]),
        ])])];
});
