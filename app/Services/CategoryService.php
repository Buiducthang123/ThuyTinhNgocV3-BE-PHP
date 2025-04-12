<?php
namespace App\Services;

use App\Repositories\Category\CategoryRepositoryInterface;

class CategoryService {
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository){
        $this->categoryRepository = $categoryRepository;
    }

    public function getAll($data){
        $paginate = $data['paginate'] ?? null;
        $with = $data['with'] ?? [];
        $filter = $data['filter'] ?? null;
        return $this->categoryRepository->getAll($paginate, $with, $filter );
    }

    public function show($id, $data = []){
        $with = $data['with'] ?? [];
        $load = $data['load'] ?? [];
        return $this->categoryRepository->show($id, $with, $load);
    }

    public function update($id, $data){

        // $newChildrenIds = $data['children'] ?? [];

        // $oldChildrenIds = $this->categoryRepository->getChildrenIds($id);

        $this->categoryRepository->update($id, $data);

        $category = $this->categoryRepository->update($id, $data);


        return $category;
    }

    public function create($data){
        return $this->categoryRepository->create($data);
    }

    public function delete($id){
        return $this->categoryRepository->delete($id);
    }
}
