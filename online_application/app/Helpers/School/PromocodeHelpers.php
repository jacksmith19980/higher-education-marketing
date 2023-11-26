<?php

namespace App\Helpers\School;

class PromocodeHelpers
{
    public static function calculateDiscount($cart, $price): float
    {
        switch ($cart['discount']['type']) {
            case 'percentage':
                return self::calculateValueFromPercentage($cart['discount']['reward'], $price['total']);
                break;
            case 'flat':
                return $cart['discount']['reward'];
                break;
            default:
                return 0;
        }
    }

    public static function calculateValueFromPercentage($percentage, $total): float
    {
        return ($total * $percentage) / 100;
    }
}
