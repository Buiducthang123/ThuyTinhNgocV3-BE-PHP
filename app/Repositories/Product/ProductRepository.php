<?php
namespace App\Repositories\Product;

use App\Enums\OrderStatus;
use App\Models\Product;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function getModel()
    {
        return Product::class;
    }

    public function getAll($paginate = null, $with = [], $filter = null, $limit = null, $search = null, $sort = null)
    {
        $query = $this->model->query();

        if (! empty($with)) {
            $query->with($with);
        }

        if ($filter) {
            $filter       = json_decode($filter, true);
            $category_id  = $filter['category_id'] ?? null;

            if ($category_id) {
                $query->where('category_id', $category_id);
            }

            //lọc theo trạng thái bán
            $is_sale = $filter['is_sale'] ?? 'all';
            switch ($is_sale) {
                case 'all':
                    break;
                case 1:
                    $query->where('is_sale', 1);
                    break;
                case 0:
                    $query->where('is_sale', 0);
                    break;
                default:
                    break;
            }

            //lọc theo khoảng giá

            $priceFrom = $filter['priceFrom'] ?? null;
            $priceTo   = $filter['priceTo'] ?? null;

            if ($priceFrom && $priceTo) {
                $query->whereBetween('price', [$priceFrom, $priceTo]);
            }

            //Lấy product chưa thuộc promotion nào
            $promotion_null = $filter['promotion_null'] ?? null;

            if ($promotion_null) {
                $query->whereNull('promotion_id');
            }
        }

        if ($sort) {
            switch ($sort) {
                case 'all':
                    break;
                case 'new':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'old':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'name_az':
                    $query->orderBy('title', 'asc');
                    break;
                case 'name_za':
                    $query->orderBy('title', 'desc');
                    break;
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'discount_asc':
                    $query->orderBy('discount', 'asc');
                    break;
                case 'discount_desc':
                    $query->orderBy('discount', 'desc');
                    break;
                default:
                    break;
            }
        }

        if ($search) {
            $search = json_decode($search, true);
            $title  = $search['title'] ?? null;

            if ($title) {
                //tìm kiếm theo tiêu đề hoặc nhà xuất bản hoặc tác giả
                //tác giả và nhà xuất bản là bảng liên kết nên cần join
                $query->where('title', 'like', '%' . $title . '%');
            }
        }

        if ($paginate) {
            return $query->paginate($paginate);
        }
        return $query->get();
    }

    public function show($id, $with = [])
    {
        $query = $this->model->query();

        if (! empty($with)) {
            $query->with($with);
        }

        $product = $query->where(function ($query) use ($id) {
            $query->where('id', $id)->orWhere('slug', $id);
        })->first();

        return $product;
    }

    public function update($id, $attributes = [])
    {
        $model = $this->model->find($id);
        DB::beginTransaction();
        try {
            if ($model) {
                $product = $model->update($attributes);
                if ($product) {
                    DB::commit();
                    return $model;
                }
                return $model;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return null;
        }

        return null;
    }

    public function create($attributes = [])
    {
        DB::beginTransaction();
        try {
            $product = $this->model->create($attributes);
            if ($product) {
                DB::commit();
                return $product;
            }
            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            return null;
        }
    }

    public function getProductByCategory($category_id, $paginate = null, $with = [], $filter = null, $limit = null, $search = null, $sort = null)
    {
        $query = $this->model->query();

        if (! empty($with)) {
            $query->with($with);
        }

        $query->where('category_id', $category_id);

        $query->where('is_sale', 1);

        if ($sort) {
            switch ($sort) {
                case 'all':
                    break;
                case 'new':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'old':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'name_az':
                    $query->orderBy('title', 'asc');
                    break;
                case 'name_za':
                    $query->orderBy('title', 'desc');
                    break;
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'discount_asc':
                    $query->orderBy('discount', 'asc');
                    break;
                case 'discount_desc':
                    $query->orderBy('discount', 'desc');
                    break;
                default:
                    break;
            }
        }

        if ($search) {
            $search = json_decode($search, true);

            $title = $search['title'] ?? null;

            if ($title) {
                $query->where('title', 'like', '%' . $title . '%');
            }
        }

        if ($paginate) {
            return $query->paginate($paginate);
        }
        return $query->get();
    }

    public function checkQuantity($id, $quantity)
    {
        $product = $this->model->find($id);
        if ($product) {
            if ($product->quantity >= $quantity) {
                return true;
            }
            return false;
        }
        return false;
    }

    public function getProductInArrId($arrId, $with = [])
    {
        $query = $this->model->query();

        if (! empty($with)) {
            $query->with($with);
        }

        $query->whereIn('id', $arrId);

        return $query->get();
    }

    public function getTop10BestSeller($start_date, $end_date, $optionShow = 'all')
    {
        $query = $this->model->query();

        $query->join('order_items', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->select(
                'products.id',
                'products.title',
                'products.cover_image',
                'products.price',
                'products.discount',
                DB::raw('SUM(order_items.quantity) as total_quantity') // Tổng số lượng
            )
            ->where('status', '!=', OrderStatus::CANCELLED)
            ->whereBetween('orders.created_at', [$start_date, $end_date])
            ->groupBy('products.id', 'products.title', 'products.cover_image', 'products.price', 'products.discount') // Chỉ group theo thông tin của product
            ->orderBy('total_quantity', 'desc')                                                        // Sắp xếp theo số lượng giảm dần
            ->limit(10);                                                                               // Giới hạn lấy 10 kết quả

        switch ($optionShow) {
            case 'all':
                break;
            case 'today':
                $query->whereDate('orders.created_at', now());
                break;
            case 'yesterday':
                $query->whereDate('orders.created_at', now()->subDay());
                break;
            case 'this_week':
                $query->whereBetween('orders.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'this_month':
                $query->whereMonth('orders.created_at', now()->month);
                break;
            case 'this_year':
                $query->whereYear('orders.created_at', now()->year);
                break;
            default:
                break;
        }

        return $query->take(10)->get();
    }

}
