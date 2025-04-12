<?php
namespace App\Repositories\Category;

use App\Models\Category;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function getModel()
    {
        return Category::class;
    }

    public function getAll($paginate = null, $with = [], $filters = null)
    {
        $query = $this->model->query();
        if (!empty($filters)) {
            $filters = json_decode($filters, true);

            $level = $filters['level'] ?? null;

            switch ($level) {
                case 'all':
                    break;
                case 'parent':
                    $query = $query->whereNull('parent_id');
                    break;
                case 'child':
                    $query = $query->whereNotNull('parent_id');
                    break;
                default:
                    break;
            }

            $name = $filters['name'] ?? null;

            if ($name) {
                $query = $query->where('name', 'like', '%' . $name . '%');
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

    public function show($id, $with = [], $load = [])
    {
        $query = $this->model->query();


        $category = $query->find($id);

        if(!$category){
            $category = $this->model->where('slug', $id)->first();
        }

        // if (!empty($with)) {
        //     $category->with($with);
        // }

        if (!empty($load)) {
            $category->load($load);
        }


        return $category;
    }

    public function getChildren($id, $load = [])
    {
        if (!empty($load)) {
            return $this->model->find($id)->children()->with($load)->get();
        }
        return $this->model->find($id)->children;
    }

    public function getChildrenIds($id)
    {
        return $this->model->find($id)->children->pluck('id')->toArray();
    }

    public function delete($id)
    {
       DB::beginTransaction();
        try {
            $category = $this->model->find($id);
            $category->delete();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }


}
