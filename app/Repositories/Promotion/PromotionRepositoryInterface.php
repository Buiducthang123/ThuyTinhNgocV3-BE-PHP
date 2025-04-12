<?php

namespace App\Repositories\Promotion;

use App\Repositories\RepositoryInterface;

interface PromotionRepositoryInterface extends RepositoryInterface
{
    public function getAll($paginate = null, $with = [], $filter = null , $sort=null);

    public function show($id, $with = []);

}
