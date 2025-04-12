<?php
namespace App\Services;

use App\Repositories\ProductTransaction\ProductTransactionRepositoryInterface;

class ProductTransactionService{

    protected $productTransactionRepository;

    public function __construct(ProductTransactionRepositoryInterface $productTransactionRepository)
    {
        $this->productTransactionRepository = $productTransactionRepository;
    }

    public function getAll($paginate = null, $with = [], $filters = [], $search = null)
    {
        return $this->productTransactionRepository->getAll($paginate, $with, $filters, $search);
    }

    public function show($id, $with = [])
    {
        return $this->productTransactionRepository->show($id, $with);
    }

    public function update($data, $id)
    {
        return $this->productTransactionRepository->update($id, $data);
    }

    public function create($data)
    {
        return $this->productTransactionRepository->create($data);
    }

}
