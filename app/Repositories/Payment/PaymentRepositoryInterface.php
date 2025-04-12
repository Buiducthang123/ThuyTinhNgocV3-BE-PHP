<?php

namespace App\Repositories\Payment;

interface PaymentRepositoryInterface{
    public function getAll($paginate = null, $with = [], $filter = null , $sort=null);

    public function getMyPayment($paginate = null, $with = [], $filter = null, $sort = null, $user_id);
}
