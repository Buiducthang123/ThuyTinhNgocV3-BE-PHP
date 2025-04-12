<?php
namespace App\Repositories\ShippingAddress;

use App\Models\ShippingAddress;
use App\Repositories\BaseRepository;

class ShippingAddressRepository extends BaseRepository implements ShippingAddressRepositoryInterface
{
    public function getModel()
    {
        return ShippingAddress::class;
    }
}
