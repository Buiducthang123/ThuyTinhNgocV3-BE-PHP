<?php
namespace App\Repositories\ProductTransaction;

use App\Enums\ProductTransactionStatus;
use App\Enums\ProductTransactionType;
use App\Models\ProductTransaction;
use App\Repositories\BaseRepository;

class ProductTransactionRepository extends BaseRepository implements ProductTransactionRepositoryInterface
{
    public function getModel()
    {
        return ProductTransaction::class;
    }

    public function getAll($paginate = null, $with = [], $filters = null, $search = null)
    {
        $query = $this->model->query();
        if (!empty($filters)) {
            $filters = json_decode($filters, true);

            $status = $filters['status'] ?? null;

            switch ($status) {
                case 'all':
                    break;
                case 'pending':
                    $query = $query->where('status', ProductTransactionStatus::PENDING);
                    break;
                case 'success':
                    $query = $query->where('status', ProductTransactionStatus::SUCCESS);
                    break;
                case 'cancel':
                    $query = $query->where('status', ProductTransactionStatus::CANCEL);
                    break;
                default:
                    break;
            }

            $type = $filters['type'] ?? null;

            switch ($type) {
                case 'all':
                    break;
                case 'export':
                    $query = $query->where('type', ProductTransactionType::EXPORT);
                    break;
                case 'import':
                    $query = $query->where('type', ProductTransactionType::IMPORT);
                    break;
                default:
                    break;
            }

            $latest = $filters['latest'] ?? null;

            switch ($latest) {
                case 'all':
                    break;
                case 'today':
                    $query = $query->whereDate('date', now());
                    break;
                case 'week':
                    $query = $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query = $query->whereMonth('date', now()->month);
                    break;
                case 'year':
                    $query = $query->whereYear('date', now()->year);
                    break;
                case 'new':
                    $query = $query->orderBy('date', 'desc');
                    break;
                case 'old':
                    $query = $query->orderBy('date', 'asc');
                    break;
                default:
                    break;
            }

            $price = $filters['price'] ?? null;

            // switch ($price) {
            //     case 'all':
            //         break;
            //     case 'low': // Giá thấp đến cao
            //         $query = $query->orderBy('price', 'asc');
            //         break;
            //     case 'high':
            //         $query = $query->orderBy('price', 'desc');
            //         break;
            //     default:
            //         break;
            // }]
        }
        if (!empty($search)) {
            $search = json_decode($search, true);

            $productName = $search['productName'] ?? null;

            if($productName){
                $query->whereHas('product', function ($query) use ($productName) {
                    $query->where('title', 'like', '%' . $productName . '%');
                });
            }
        }

        if (!empty($with)) {
            $query = $query->with($with);
        }
        if ($paginate) {
            return $query->paginate($paginate);
        }
        return $query->get();
    }


    public function show($id, $with = [])
    {
        $query = $this->model->query();
        if (!empty($with)) {
            $query = $query->with($with);
        }
        return $query->find($id);
    }

}
