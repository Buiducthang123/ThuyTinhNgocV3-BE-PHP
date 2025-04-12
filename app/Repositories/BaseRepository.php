<?php

namespace App\Repositories;

use App\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class BaseRepository implements RepositoryInterface
{
    //model muốn tương tác
    protected $model;

    //khởi tạo
    public function __construct()
    {
        $this->setModel();
    }

    //lấy model tương ứng
    abstract public function getModel();

    /**
     * Set model
     */
    public function setModel()
    {
        $this->model = app()->make(
            $this->getModel()
        );
    }

    public function getAll($paginate = null, $with = [])
    {
        $query = $this->model->query();
        if (!empty($with)) {
            $query = $query->with($with);
        }
        if ($paginate) {
            return $query->paginate($paginate);
        }
        return $query->get();
    }

    public function find($id)
    {
        $result = $this->model->find($id);

        return $result;
    }

    public function create($attributes = [])
    {
       try {
            $result = $this->model->create($attributes);
            return $result;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update($id, $attributes = [])
    {
        $result = $this->find($id);

        if(!$result) {
            throw new ModelNotFoundException('Không tìm thấy dữ liệu có id = ' . $id);
        }
        try {
            $isUpdated = $result->update($attributes);
            if (!$isUpdated) {
                throw new \Exception('Cập nhật không thành công cho bản ghi id = ' . $id);
            }
            return $result;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function delete($id)
    {
        $result = $this->find($id);

        if(!$result) {
            throw new ModelNotFoundException('Không tìm thấy dữ liệu có id = ' . $id);
        }
        try {
            $isDeleted = $result->delete();
            if (!$isDeleted) {
                throw new \Exception('Xóa không thành công cho bản ghi id = ' . $id);
            }
            return $result;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
