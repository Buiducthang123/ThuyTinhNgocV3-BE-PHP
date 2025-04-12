<?php

namespace App\Http\Controllers;

use App\Services\DiscountTiersService;
use Illuminate\Http\Request;

class DiscountTiersController extends Controller
{
    protected $discountTiersService;

    public function __construct(DiscountTiersService $discountTiersService)
    {
        $this->discountTiersService = $discountTiersService;
    }

    public function createMany(Request $request)
    {
        $result = $this->discountTiersService->createMany($request->all());
        if ($result) {
            return response()->json(['message' => 'Tạo mới thành công'], 201);
        }
        return response()->json(['message' => 'Có lỗi xảy ra'], 500);
    }

    public function create(Request $request)
    {
        $result = $this->discountTiersService->create($request->all());
        if ($result) {
            return response()->json(['message' => 'Tạo mới thành công'], 201);
        }
        return response()->json(['message' => 'Có lỗi xảy ra'], 500);
    }


}
