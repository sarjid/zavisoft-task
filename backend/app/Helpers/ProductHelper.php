<?php

namespace App\Helpers;

class ProductHelper
{

    public static function calculateCurrentPrice($price, $discountType, $discount)
    {

        $totalDiscount = 0;
        if ($discountType === 'fixed') {
            $totalDiscount = $discount;
        } elseif ($discountType === 'percent') {
            $totalDiscount = ($discount / 100) * $price;
        }
        return round($price - $totalDiscount);
    }


    public static function getStockStatus($stock)
    {
        return $stock === 0 ? 'Out Of Stock' : 'In Stock';
    }
}
