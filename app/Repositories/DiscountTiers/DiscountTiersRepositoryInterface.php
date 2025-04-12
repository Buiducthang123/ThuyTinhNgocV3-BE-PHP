<?php
namespace App\Repositories\DiscountTiers;

use App\Repositories\RepositoryInterface;

interface DiscountTiersRepositoryInterface extends RepositoryInterface
{
    public function createMany($attributes = []);

    public function create($attributes = []);
}
