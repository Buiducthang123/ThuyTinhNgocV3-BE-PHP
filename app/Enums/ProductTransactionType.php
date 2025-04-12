<?php
namespace App\Enums;

class ProductTransactionType {
    const IMPORT = 1; // Nhập sản phẩm
    const EXPORT = 2; // Xuất sản phẩm

    static public function getLabel($value) {
        switch ($value) {
            case self::IMPORT:
                return 'Nhập sản phẩm';
            case self::EXPORT:
                return 'Xuất sản phẩm';
            default:
                return '';
        }
    }

    static public function getValues() {
        return [
            self::IMPORT,
            self::EXPORT,
        ];
    }
}
