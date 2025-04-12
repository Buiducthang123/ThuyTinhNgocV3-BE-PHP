<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userServices;

    public function __construct(UserService $userServices)
    {
        $this->userServices = $userServices;
    }

    public function index(Request $request)
    {
        return $this->userServices->getAll($request->all());
    }

    public function store(Request $request)
    {
        return $request->all();
    }

    public function updateMe(Request $request)
    {
       return $this->userServices->updateMe($request->all());
    }

    public function show($id, Request $request)
    {
        return $this->userServices->show($id, $request->all());
    }

    public function update($userId, UserUpdateRequest $request)
    {
        return $this->userServices->update($userId, $request->all());
    }
}
