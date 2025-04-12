<?php
namespace App\Repositories\OrderItem;

use App\Models\OrderItem;
use App\Repositories\BaseRepository;

class OrderItemRepository extends BaseRepository implements OrderItemRepositoryInterface{

    public function getModel()
    {
        return OrderItem::class;
    }
}
