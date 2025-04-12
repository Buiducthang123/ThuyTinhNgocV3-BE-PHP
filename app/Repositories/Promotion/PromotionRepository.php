<?php

namespace App\Repositories\Promotion;

use App\Models\Promotion;
use App\Repositories\BaseRepository;
use Carbon\Carbon;

class PromotionRepository extends BaseRepository implements PromotionRepositoryInterface
{
    public function getModel()
    {
        return Promotion::class;
    }

    public function getAll($paginate = null, $with = [], $filter = null, $sort = null)
    {
        $query = $this->model->query();

        $query->with(['products']);

        $query->orderBy('start_date', 'asc');

        if ($filter) {
            $filter = json_decode($filter, true);
            $title = $filter['title'] ?? null;
            $start_date = $filter['start_date'] ?? null;
            $end_date = $filter['end_date'] ?? null;

            if ($title) {
                $query->where('title', 'like', '%' . $title . '%');
            }

            if ($start_date) {
                //promotion chưa kết thúc tính từ ngày hiện tại
                $query->where('end_date', '>=', Carbon::parse($start_date));
            }

            if ($end_date) {
                $query->where('end_date', '<=', Carbon::parse($end_date));
            }

            if($start_date && $end_date){
                $query->where('start_date', '>=', Carbon::parse($start_date))
                ->where('end_date', '<=', Carbon::parse($end_date));
            }
        }

        if ($sort) {
            $sort = json_decode($sort, true);
            switch ($sort) {
                case 'asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    break;
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
        if (!empty($with)) {
            $query->with($with);
        }
       $query->where('id', $id)->orWhere('slug', $id);
       return $query->first();
    }
}
