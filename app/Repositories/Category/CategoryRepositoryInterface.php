<?php
namespace App\Repositories\Category;

use App\Repositories\RepositoryInterface;

interface CategoryRepositoryInterface extends RepositoryInterface
{
    public function getAll($paginate = null, $with = [], $filters = []);

    public function show($id, $with = [], $load = []);

    public function getChildren($id, $load = []);

    public function getChildrenIds($id);

    public function delete($id);
}
