<?php
namespace App\Repositories\Review;

use App\Models\Review;
use App\Repositories\BaseRepository;

class ReviewRepository extends BaseRepository implements ReviewRepositoryInterface
{
    public function getModel()
    {
        return Review::class;
    }

    public function getAll($paginate = null, $with = [], $filter = null, $sort = null)
    {
        $query =$this->model->query();
        if($with){
            $query->with($with);
        }

        if($sort){
            switch ($sort){
               case 'old':
                   $query->orderBy('created_at', 'asc');
                   break;
                case 'new':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    break;
            }
        }

        if($filter){
            $filter = json_decode($filter, true);
            $rating = $filter['rating'] ?? null;
            if($rating){
                $query->where('rating', $rating);
            }
        }

        return $paginate ? $query->paginate($paginate) : $query->get();
    }

    public function showByProduct($paginate = 10, $product_id, $with = [])
    {
        $query = $this->model->query();
        if($with){
            $query->with($with);
        }
        return $query->where('product_id', $product_id)->paginate($paginate);
    }
}
