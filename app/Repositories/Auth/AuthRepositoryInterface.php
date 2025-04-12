<?php
namespace App\Repositories\Auth;

use App\Repositories\RepositoryInterface;

interface AuthRepositoryInterface extends RepositoryInterface
{
    public function register($data);
    public function login($data);
    public function logout($isLogoutAll = false);
    public function user();
    public function refresh();
    public function loginGoogle();
    public function loginGoogleCallback();
    public function findUser($field);
}
