<?php

return [
    'store' => [
        'name' => env('STORE_NAME', 'Toronto Textile'),
        'tagline' => env('STORE_TAGLINE', 'Canadian essentials for every season.'),
        'support_email' => env('STORE_SUPPORT_EMAIL', 'support@torontotextile.ca'),
        'phone' => env('STORE_PHONE', '+1 437 551 9575'),
        'city' => env('STORE_CITY', 'Toronto, Ontario'),
        'country' => env('STORE_COUNTRY', 'Canada'),
    ],
    'order_notification_emails' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('ORDER_NOTIFICATION_EMAILS', 'support@torontotextile.ca'))
    ))),
    'local_json_path' => env('CATALOG_LOCAL_JSON_PATH', base_path()),
    'default_brand' => env('CATALOG_DEFAULT_BRAND', 'khanabadosh'),
    'brands' => [
        'toronto-textile' => [
            'label' => 'Toronto Textile',
            'source_url' => env('TORONTO_TEXTILE_SOURCE_URL', ''),
            'enabled' => false,
        ],
        'khanabadosh' => [
            'label' => 'Khanabadosh',
            'source_url' => env('KHANABADOSH_SOURCE_URL', 'https://khanabadoshonline.com'),
            'enabled' => true,
        ],
        'demo-brand' => [
            'label' => 'Studio Vale',
            'source_url' => env('DEMO_BRAND_SOURCE_URL', ''),
            'enabled' => true,
        ],
        'northline' => [
            'label' => 'Northline',
            'source_url' => env('NORTHLINE_SOURCE_URL', ''),
            'enabled' => true,
        ],
        'harbour-loom' => [
            'label' => 'Harbour Loom',
            'source_url' => env('HARBOUR_LOOM_SOURCE_URL', ''),
            'enabled' => true,
        ],
        'sable-atelier' => [
            'label' => 'Sable Atelier',
            'source_url' => env('SABLE_ATELIER_SOURCE_URL', ''),
            'enabled' => true,
        ],
    ],
    'categories' => [
        'primary' => [
            ['label' => 'Women', 'slug' => 'women-all'],
            ['label' => 'Men', 'slug' => 'men-all'],
            ['label' => 'Outerwear', 'slug' => 'outerwear'],
            ['label' => 'Activewear', 'slug' => 'activewear'],
            ['label' => 'Accessories', 'slug' => 'accessories'],
            ['label' => 'Kids & Baby', 'slug' => 'kids-baby'],
            ['label' => 'Home Textiles', 'slug' => 'home-textiles'],
        ],
        'accessories' => [
            ['label' => 'Caps', 'slug' => 'caps'],
            ['label' => 'Glasses', 'slug' => 'glasses'],
            ['label' => 'Watches', 'slug' => 'watches'],
            ['label' => 'Bags', 'slug' => 'bags'],
            ['label' => 'Scarves', 'slug' => 'scarves'],
            ['label' => 'Belts', 'slug' => 'belts'],
        ],
    ],
];
