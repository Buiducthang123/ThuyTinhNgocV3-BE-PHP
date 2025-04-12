<?php
namespace App\Repositories\User;

use App\Enums\OrderStatus;
use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\Role\RoleRepository;
use App\Repositories\User\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        parent::__construct();
        $this->roleRepository = $roleRepository;
    }
    public function getModel()
    {
        return User::class;
    }

    public function getAll($paginate = null, $status = null, $search = null, $with = [], $role = null)
    {
        $query = $this->model->query();

        if ($search !== null) {
            $query->where('full_name', 'like', '%'.$search.'%')->orWhere('email', 'like', '%'.$search.'%');
        }

        if ($role !== null || $role !== '') {;
            $role = $this->roleRepository->getRoleByName($role);
            if ($role) {
                $query->where('role_id', $role->id);
            }
        }

        if ($status !== null) {
            $query->where('status', $status);
        }

        if (is_array($with) && count($with) > 0) {
            $query->with($with);
        }

        if ($paginate !== null) {
            return $query->paginate($paginate);
        }

        return $query->get();
    }


    public function show($id, $with = [])
    {
        $query = $this->model->query();

        if (is_array($with) && count($with) > 0) {
            $query->with($with);
        }

        return $query->find($id);
    }

    public function getTop10Customer($start_date, $end_date, $optionShow = 'all')
    {
        $query = $this->model->query();
        //đơn hàng mua thành công
        $query->join('orders', 'users.id', '=', 'orders.user_id')
            ->selectRaw('users.id, users.full_name,users.avatar, users.email, count(orders.user_id) as total_order')
            ->where('orders.status', OrderStatus::DELIVERED)
            ->whereBetween('orders.created_at', [$start_date, $end_date])
            ->groupBy('users.id', 'users.full_name', 'users.email','users.avatar')
            ->orderBy('total_order', 'desc');

        if ($optionShow == 'all') {
            return $query->get();
        }

        return $query->take(10)->get();
    }
}
