<?php

return [
    'base_url' => env('REMOTE_STORE_BASE_URL', 'https://khanabadoshonline.com'),
    'products_endpoint' => env('REMOTE_STORE_PRODUCTS_ENDPOINT', '/products.json'),
    'collections_endpoint' => env('REMOTE_STORE_COLLECTIONS_ENDPOINT', '/collections.json'),
    'products_query' => [
        'limit' => env('REMOTE_STORE_PRODUCTS_LIMIT', 250),
    ],
    'http' => [
        'timeout' => env('REMOTE_STORE_HTTP_TIMEOUT', 20),
        'retry' => [
            'times' => env('REMOTE_STORE_HTTP_RETRY_TIMES', 2),
            'sleep' => env('REMOTE_STORE_HTTP_RETRY_SLEEP', 250),
        ],
    ],
];

