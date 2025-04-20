<?php

namespace App\Http\Controllers;

use App\Enums\AccountStatus;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use App\Services\RoleService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;
    protected $roleService;

    public function __construct(AuthService $authService, RoleService $roleService)
    {
        $this->authService = $authService;
        $this->roleService = $roleService;
    }

    public function register(RegisterRequest $request)
    {

        $email = $request->email;
        $password = $request->password;
        $fullName = $request->fullName;
        $companyName = $request->companyName;
        $companyAddress = $request->companyAddress;
        $companyPhoneNumber = $request->companyPhoneNumber;
        $companyTaxCode = $request->companyTaxCode;
        $contactPersonName = $request->contactPersonName;
        $representativeIdCard = $request->representativeIdCard;
        $representativeIdCardDate = $request->representativeIdCardDate;
        $contactPersonPosition = $request->contactPersonPosition;
        $roleId = null;
        $status = AccountStatus::NOT_ACTIVE;

        if ($companyName && $companyAddress && $companyPhoneNumber && $companyTaxCode && $contactPersonName && $representativeIdCard && $representativeIdCardDate && $contactPersonPosition) {
            $role = $this->roleService->getRoleByName('company');
            if (!$role) {
                return response()->json(['message' => 'Không thể tạo tài khoản, vui lòng liên hệ quản trị viên1'], 404);
            }
            $roleId = $role->id;
        } else {
            $role = $this->roleService->getRoleByName('user');
            if (!$role) {
                return response()->json(['message' => 'Không thể tạo tài khoản, vui lòng liên hệ quản trị viên'], 404);
            }
            $roleId = $role->id;
            $status = AccountStatus::ACTIVE;
        }

        $data = [
            'email' => $email,
            'password' => $password,
            'full_name' => $fullName,
            'role_id' => $roleId,
            'status' => $status,
        ];

        $response = $this->authService->register($data);
        return response()->json($response);
    }

    public function login(Request $request)
    {
        $data = $request->all();
        $response = $this->authService->login($data);
        return response()->json($response);
    }

    public function logout(Request $request)
    {
        $isLogoutAll = $request->isLogoutAll ?? false;
        $response = $this->authService->logout($isLogoutAll);
        return response()->json($response);
    }

    public function user()
    {
        $response = $this->authService->user();
        return response()->json($response);
    }

    public function loginGoogle()
    {
        return $this->authService->loginGoogle();
    }

    public function loginGoogleCallback()
    {
        return $this->authService->loginGoogleCallback();
    }
}
