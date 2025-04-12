<?php

namespace App\Services;

use App\Repositories\DiscountTiers\DiscountTiersRepositoryInterface;

class DiscountTiersService
{

    protected $discountTiersRepo;

    /**
     * Class constructor.
     */
    public function __construct(DiscountTiersRepositoryInterface $discountTiersRepo)
    {
        $this->discountTiersRepo = $discountTiersRepo;
    }

    public function createMany($data = [])
    {
        return $this->discountTiersRepo->createMany($data);
    }

    public function create($data = [])
    {
        return $this->discountTiersRepo->create($data);
    }
}
