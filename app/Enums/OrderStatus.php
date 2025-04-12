<?php
namespace App\Enums;

class OrderStatus
{
    const PENDING = 1; // Chờ duyệt
    const APPROVED = 2; // Đã duyệt
    const PREPARING = 3; // Đang chuẩn bị hàng
    const SHIPPING = 4; // Đang vận chuyển
    const DELIVERED = 5; // Đã giao hàng
    const CANCELLED = 6; // Đã hủy
    const NOT_PAID = 7; // Chưa chuyển khoản
    const REQUEST_CANCEL = 8; // Yêu cầu hủy

    public static function getStatuses()
    {
        return [
            self::PENDING => 'Chờ duyệt',
            self::APPROVED => 'Đã duyệt',
            self::PREPARING => 'Đang chuẩn bị hàng',
            self::SHIPPING => 'Đang vận chuyển',
            self::DELIVERED => 'Đã giao hàng',
            self::CANCELLED => 'Đã hủy',
            self::NOT_PAID => 'Chưa chuyển khoản',
            self::REQUEST_CANCEL => 'Yêu cầu hủy',
        ];
    }

    public static function getLabelStatus($status)
    {
        $statuses = self::getStatuses();
        return $statuses[$status] ?? '';
    }

    public static function getAllStatuses()
    {
        return [
            self::PENDING,
            self::APPROVED,
            self::PREPARING,
            self::SHIPPING,
            self::DELIVERED,
            self::CANCELLED,
            self::NOT_PAID,
            self::REQUEST_CANCEL,
        ];
    }
}
