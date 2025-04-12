<?php
namespace App\Repositories\Order;

use App\Repositories\RepositoryInterface;

interface OrderRepositoryInterface extends RepositoryInterface
{
    public function getAll($paginate=null ,$with = [], $filter=null, $sort =null);

    public function show($id, $with = []);

    public function myOrder($paginate = null, $with = [], $filter = null, $sort = null);

    public function sendMailOrderStatus($order, $user);

    public function getTotalRevenue($start_date = null, $end_date = null);

    public function getRevenueByTime($start_date, $end_date,$optionShow = 'all');

    public function getRevenueByRange($start_date, $end_date);
}
