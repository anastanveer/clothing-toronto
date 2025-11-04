<?php

return [
    'currency_code' => env('COMMERCE_CURRENCY_CODE', 'CAD'),
    'currency_symbol' => env('COMMERCE_CURRENCY_SYMBOL', 'CA$'),
    'tax_rate' => (float) env('COMMERCE_TAX_RATE', 0.13),
    'exchange' => [
        'enabled' => env('COMMERCE_EXCHANGE_ENABLED', true),
        'base_currency' => env('COMMERCE_EXCHANGE_BASE', 'PKR'),
        'target_currency' => env('COMMERCE_EXCHANGE_TARGET', 'CAD'),
        'rate' => (float) env('COMMERCE_EXCHANGE_RATE', 0.0045), // Base currency to target multiplier.
    ],
];

