<?php
namespace App\Repositories\Auth;

use App\Mail\VerifyEmail;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;

class AuthRepository extends BaseRepository implements AuthRepositoryInterface
{
    public function getModel()
    {
        return User::class;
    }

    public function register($data)
    {
        try {
            DB::beginTransaction();
            $data['password'] = bcrypt($data['password']);
            $user = $this->model->create($data);
            if ($user) {
                Mail::to($user->email)->queue(new VerifyEmail($user));
                DB::commit();
                return $user;
            }
            return abort(404, 'Tạo tài khoản thất bại, vui lòng liên hệ quản trị viên');
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

    public function login($data)
    {
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $user = $this->model->where('email', $email)->first();
        if (!$user) {
            return abort(404, 'Email đăng nhập không chính xác');
        }
        $credential = Auth::attempt(['email' => $email, 'password' => $password]);
        if (!$credential) {
            return abort(404, 'Mật khẩu không chính xác');
        }
        $user = $user->load('role');
        return $user;
    }

    public function logout($isLogoutAll = false)
{
    try {
        $user = Auth::user();
        if ($user) {
            if ($isLogoutAll) {
                $user->tokens()->delete();
            } else {
                $user->currentAccessToken()->delete();
            }
            return ;
        }
        return abort(404, 'Không tìm thấy người dùng');
    } catch (\Exception $e) {

        return abort(404, $e->getMessage());
    }
}


    public function loginGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect()->getTargetUrl();
    }

    public function loginGoogleCallback()
    {
        return 'loginGoogleCallback';
    }

    public function findUser($field)
    {
        if ($field['email']) {
            return $this->model->where('email', $field['email'])->first();
        }
        return null;
    }

    public function user()
    {
        return Auth::user();
    }

    public function refresh()
    {
        return 'refresh';
    }

}
