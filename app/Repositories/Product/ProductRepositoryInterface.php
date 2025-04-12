<?php
namespace App\Repositories\Product;

use App\Repositories\RepositoryInterface;

interface ProductRepositoryInterface extends RepositoryInterface
{
    public function getAll($paginate =null, $with = [], $filter = null, $limit = null, $search = null, $sort = null);

    public function show($id, $with = []);

    public function create($data = []);

    public function getProductByCategory($category_id,$paginate =null, $with = [], $filter = null, $limit = null, $search = null, $sort = null);

    // kiểm tra số lượng sản phẩm còn trong kho
    public function checkQuantity($id, $quantity);

    public function getProductInArrId($arrId, $with = []);

    //thống kê top 10 sản phẩm bán chạy
    public function getTop10BestSeller($start_date, $end_date, $optionShow = 'all');
}
