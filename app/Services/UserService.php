<?php
namespace App\Services;

use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class UserService {
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAll($data)
    {
        $status = $data['status'] ?? null;
        $paginate = $data['paginate'] ?? null;
        $search = $data['search'] ?? null;
        $with = $data['with'] ?? [];
        $role = $data['role'] ?? null;
        return $this->userRepository->getAll($paginate, $status, $search, $with, $role);
    }

    public function updateMe($data)
    {
        $user = Auth::user();

        if (!$user) {
            return abort(403, 'Bạn không có quyền thực hiện hành động này');
        }

        $dataUpdate = [
            'full_name' => $data['fullName'] ?? $user->full_name,
            'avatar' => $data['avatar'] ?? $user->avatar,
        ];

        $user = $this->userRepository->update($user->id,$dataUpdate);

        if (!$user) {
            return abort(500, 'Có lỗi xảy ra');
        }
        return $user;
    }

    public function show($id, $data)
    {
        $with = $data['with'] ?? [];
        $user =  $this->userRepository->show($id, $with);

        if (!$user) {
            return abort(404, 'Không tìm thấy người dùng');
        }
        return $user;
    }

    public function update($id, $data)
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return abort(404, 'Không tìm thấy người dùng');
        }

        $dataUpdate = [
            'full_name' => $data['fullName']?? $user->full_name,
            'email' => $data['email'] ?? $user->email,
            'role_id' => $data['role'] ?? $user->role_id,
            'status' => $data['status'] ?? $user->status,
            'avatar' => $data['avatar'] ?? $user->avatar,
        ];

        $user = $this->userRepository->update($id, $dataUpdate);

        if (!$user) {
            return abort(500, 'Có lỗi xảy ra');
        }
        return $user;
    }

    public function delete($id)
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return abort(404, 'Không tìm thấy người dùng');
        }

        $user = $this->userRepository->delete($id);

        if (!$user) {
            return abort(500, 'Có lỗi xảy ra');
        }
        return response()->json([
            'message' => 'Xóa người dùng thành công'
        ]);
    }
}
