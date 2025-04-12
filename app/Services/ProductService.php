<?php
namespace App\Services;

use App\Repositories\Product\ProductRepositoryInterface;

class ProductService{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository){
        $this->productRepository = $productRepository;
    }

    public function getAll($data){

        $paginate = $data['paginate'] ?? null;
        $with = $data['with'] ?? [];
        $filter = $data['filter'] ?? null;
        $sort = $data['sort'] ?? null;
        $limit = $data['limit'] ?? null;
        $search = $data['search'] ?? null;
        return $this->productRepository->getAll($paginate, $with, $filter, $limit, $search,$sort);
    }
    public function create($data){
        return $this->productRepository->create($data);
    }

    public function update($data, $id){
        return $this->productRepository->update($id, $data);
    }

    public function delete($id){
        return $this->productRepository->delete($id);
    }

    public function show($id, $data){
        $with = $data['with'] ?? [];
        return $this->productRepository->show($id, $with);
    }

    public function getProductByCategory($category_id, $data){
        $paginate = $data['paginate'] ?? null;
        $with = $data['with'] ?? [];
        $filter = $data['filter'] ?? null;
        $sort = $data['sort'] ?? null;
        $limit = $data['limit'] ?? null;
        $search = $data['search'] ?? null;
        return $this->productRepository->getProductByCategory($category_id, $paginate, $with, $filter, $limit, $search, $sort);
    }

    public function checkQuantity($id, $quantity){
        return $this->productRepository->checkQuantity($id, $quantity);
    }
}
