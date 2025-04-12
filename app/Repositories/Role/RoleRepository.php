<?php

namespace App\Repositories\Role;
use App\Models\Role;
use App\Repositories\BaseRepository;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    public function getModel()
    {
        return Role::class;
    }

    public function getRoleByName($name)
    {
        return $this->model->where('name', $name)->first();
    }

}
