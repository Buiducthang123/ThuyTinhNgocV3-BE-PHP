<?php
namespace App\Enums;

class PaymentType
{
    const DEPOSIT = 1; // Nạp tiền
    const REFUND = 2; // Hoàn tiền

    public static function getValues(){
        return [
            self::DEPOSIT,
            self::REFUND,
        ];
    }

}
