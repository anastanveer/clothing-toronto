<?php

namespace App\Support;

use Illuminate\Support\Facades\Config;

class Money
{
    public static function format(float $amount, bool $convert = true): string
    {
        if ($convert) {
            $amount = self::convertToDisplay($amount);
        }

        $symbol = Config::get('commerce.currency_symbol', '$');

        return $symbol . number_format($amount, 2);
    }

    public static function convertToDisplay(float $amount): float
    {
        if (! Config::get('commerce.exchange.enabled', false)) {
            return round($amount, 2);
        }

        $rate = (float) Config::get('commerce.exchange.rate', 1);

        return round($amount * $rate, 2);
    }

    public static function convertFromBase(float $amount): float
    {
        return self::convertToDisplay($amount);
    }
}
