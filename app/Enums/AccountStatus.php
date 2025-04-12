<?php
namespace App\Enums;

class AccountStatus
{

    const ACTIVE = 1; // Hoạt động
    const BLOCKED = 2; // Bị chặn
    const NOT_ACTIVE = 3; // Chưa kích hoạt

    public static function getValues()
    {
        return [
            self::ACTIVE,
            self::BLOCKED,
            self::NOT_ACTIVE
        ];
    }
}
