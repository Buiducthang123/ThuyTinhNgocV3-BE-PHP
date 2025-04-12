<?php
namespace App\Repositories\ProductTransaction;

use App\Repositories\RepositoryInterface;

interface ProductTransactionRepositoryInterface extends RepositoryInterface{
    public function getAll($paginate = null, $with = [], $filters = null, $search = null);

    public function show($id, $with = []);
}
