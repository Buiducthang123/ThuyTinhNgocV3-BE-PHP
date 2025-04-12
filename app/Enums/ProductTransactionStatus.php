<?php
namespace App\Enums;

class ProductTransactionStatus{
    const PENDING = 1; // Đang chờ
    const SUCCESS = 2; // Thành công
    const CANCEL = 3; // Hủy

    static public function getLabel($value){
        switch($value){
            case self::PENDING:
                return 'Đang chờ';
            case self::SUCCESS:
                return 'Thành công';
            case self::CANCEL:
                return 'Hủy';
            default:
                return '';
        }
    }

    static public function getValues(){
        return [
            self::PENDING,
            self::SUCCESS,
            self::CANCEL,
        ];
    }
}
