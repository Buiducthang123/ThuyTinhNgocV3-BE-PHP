<?php
namespace App\Repositories\Review;

use App\Repositories\RepositoryInterface;

interface ReviewRepositoryInterface extends RepositoryInterface{
    public function getAll($paginate = null, $with = [], $filter = null, $sort = null);

    public function showByProduct($paginate=10, $product_id, $with = []);
}
