<?php

return [
    'welcome_bundle' => [
        'enabled' => true,
        'coupons' => [
            [
                'code' => 'WELCOME30',
                'status' => \App\Models\UserCoupon::STATUS_AVAILABLE,
                'max_assignments' => 50,
                'available_in_days' => 0,
            ],
            [
                'code' => 'LOYALTY20',
                'status' => \App\Models\UserCoupon::STATUS_PENDING,
                'available_in_days' => 10,
            ],
            [
                'code' => 'RESTOCK10',
                'status' => \App\Models\UserCoupon::STATUS_PENDING,
                'available_in_days' => 20,
            ],
            [
                'code' => 'GIFT15',
                'status' => \App\Models\UserCoupon::STATUS_PENDING,
                'available_in_days' => 30,
            ],
        ],
    ],
];
