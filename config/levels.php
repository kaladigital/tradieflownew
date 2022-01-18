<?php

return [
    'gain' => [
        'sell' => 1,
        'feedback' => 1.5,
    ],
    'vendor' => [
        1 => [
            'name' => 'Shestyorka',
            'xp_required' => 0,
            'fee' => 5.00,
            'default' => true,
        ],
        2 => [
            'name' => 'Boyevik',
            'xp_required' => 15000,
            'fee' => 3.50,
        ],
        3 => [
            'name' => 'Brigadier',
            'xp_required' => 25000,
            'fee' => 2.00,
        ],
        4 => [
            'name' => 'Obshchak',
            'xp_required' => 75000,
            'fee' => 1.50,
        ],
        5 => [
            'name' => 'Pakhan',
            'xp_required' => 150000,
            'fee' => 1.00,
        ],
        6 => [
            'name' => 'Pakhan',
            'xp_required' => 1000000,
            'fee' => 0.00,
            'reachable' => false,
        ],
    ],
];
