<?php

namespace App\Services;

use App\Repositories\ShippingAddress\ShippingAddressRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class ShippingAddressService
{
    protected $shippingAddressRepository;

    public function __construct(ShippingAddressRepositoryInterface $shippingAddressRepository)
    {
        $this->shippingAddressRepository = $shippingAddressRepository;
    }

    public function create($data)
    {
        $userAddress = Auth::user()->load('shippingAddresses');
        $addressCount = $userAddress->shippingAddresses->count();

        if ($addressCount == 0) {
            $data['is_default'] = true;
        } else {
            $isDefault = $data['is_default'];

            if ($isDefault) {
                foreach ($userAddress->shippingAddresses as $address) {
                    $address->is_default = false;
                    $address->save();
                }
            }
        }

        return $this->shippingAddressRepository->create($data);
    }

    public function update($id, $data)
    {
        $userAddress = Auth::user()->load('shippingAddresses');
        $addressCount = $userAddress->shippingAddresses->count();
        if ($addressCount == 0) {
            $data['is_default'] = true;
        } else {
            $isDefault = $data['is_default'];

            if ($isDefault) {
                foreach ($userAddress->shippingAddresses as $address) {
                    $address->is_default = false;
                    $address->save();
                }
            }
        }
        return $this->shippingAddressRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->shippingAddressRepository->delete($id);
    }
}
