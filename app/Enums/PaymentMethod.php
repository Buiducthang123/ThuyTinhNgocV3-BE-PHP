<?php
namespace App\Enums;
class PaymentMethod {
    //Thanh toán khi nhận hàng

    const COD = 1;

    //Chuyển khoản ngân hàng

    const BANK_TRANSFER = 2;

    public static function getMethods()
    {
        return [
            self::COD => 'Thanh toán khi nhận hàng',
            self::BANK_TRANSFER => 'Chuyển khoản ngân hàng',
        ];
    }

   //get label by value

    public static function getLabel($value)
    {
        $methods = self::getMethods();
        return $methods[$value] ?? '';
    }

    public static function getAllMethods()
    {
        return [
            self::COD,
            self::BANK_TRANSFER,
        ];
    }
}
