<?php

namespace App\Services;
use App\Enums\AccountStatus;
use App\Repositories\Auth\AuthRepositoryInterface;
use App\Repositories\Role\RoleRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthService
{
    protected $authRepository;
    protected $roleRepository;

    protected $roleService;

    public function __construct(AuthRepositoryInterface $authRepository, RoleRepositoryInterface $roleRepository, RoleService $roleService)
    {
        $this->authRepository = $authRepository;
        $this->roleRepository = $roleRepository;
        $this->roleService = $roleService;
    }
    public function register($data)
    {
        $user = $this->authRepository->register($data);
        // $tokenName = 'WebToken_' . now()->format('Y_m_d_H_i_s');
        // $token = $user->createToken($tokenName, ["*"], now()->addMonth())->plainTextToken;
        $message = $user->status == AccountStatus::ACTIVE ? 'Đăng ký thành công' : 'Đăng ký thành công, vui lòng chờ xác nhận từ quản trị viên';
        return [
            'user' => $user,
            // 'token' => $token,
            'message' => $message,
        ];
    }
    public function login($data)
    {
        $user = $this->authRepository->login($data);
        $tokenName = 'WebToken_' . now()->format('Y_m_d_H_i_s');
        $token = $user->createToken($tokenName, ["*"], now()->addMonth())->plainTextToken;
        return [
            'user' => $user,
            'token' => $token,
            'message' => 'Đăng nhập thành công',
        ];
    }
    public function logout($isLogoutAll = false)
    {
        return $this->authRepository->logout($isLogoutAll);
    }
    public function user()
    {
        $user = $this->authRepository->user()->load(['role','shippingAddresses']);
        if ($user) {
            return $user;
        }
        return abort(404, 'Không tìm thấy người dùng');
    }

    public function loginGoogle()
    {
         return $this->authRepository->loginGoogle();
    }

    public function loginGoogleCallback()
    {
        try {
            // Lấy thông tin người dùng từ Google bằng mã code
            $userSocial = Socialite::driver('google')->stateless()->user();

            $user = $this->authRepository->findUser(['email' => $userSocial->getEmail()]);

            if ($user) {
                Auth::login($user);
            } else {
                $role = $this->roleService->getRoleByName('user');
                if (!$role) {
                    return response()->json(['message' => 'Không thể tạo tài khoản, vui lòng liên hệ quản trị viên'], 404);
                }
                $data = [
                    'full_name' => $userSocial->getName(),
                    'email' => $userSocial->getEmail(),
                    'role_id'=> $role->id,
                    'password' => Hash::make(uniqid()),
                    'google_id' => $userSocial->getId(),
                    'avatar' => $userSocial->getAvatar(),
                    'status' => AccountStatus::ACTIVE,
                ];
                $user =  $this->authRepository->register($data);
            }
            $tokenName = 'SocialLoginToken_'.now();
            $token = $user->createToken($tokenName)->plainTextToken;
            return redirect()->away(config('app.frontend_url').'/auth/google?token='.$token);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

}
