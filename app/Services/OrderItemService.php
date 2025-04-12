<?php
namespace App\Services;

use App\Repositories\OrderItem\OrderItemRepositoryInterface;

class OrderItemService
{
    protected $orderItemRepository;

    public function __construct(OrderItemRepositoryInterface $orderItemRepository)
    {
        $this->orderItemRepository = $orderItemRepository;
    }
}
