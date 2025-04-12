<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShippingAddressRequest;
use App\Models\ShippingAddress;
use App\Services\ShippingAddressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShippingAddressController extends Controller
{

    protected $shippingAddressService;

    public function __construct(ShippingAddressService $shippingAddressService){
        $this->shippingAddressService = $shippingAddressService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(ShippingAddressRequest $request)
    {
        try{
            $data = $request->validated();
            $data['user_id'] = Auth::id();
            $shippingAddress = $this->shippingAddressService->create($data);
            return response()->json(['message' => 'Đã thêm địa chỉ giao hàng thành công.', 'data' => $shippingAddress], 201);
        }
        catch(\Exception $e){
            // return response()->json(['message' => $e->getMessage()], 500);
            return response()->json(['message' => 'Đã xảy ra lỗi, vui lòng thử lại sau.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ShippingAddress $shippingAddress)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ShippingAddressRequest $request, $id)
    {
        try{
            $data = $request->validated();
            $shippingAddress = $this->shippingAddressService->update($id, $data);
            return response()->json(['message' => 'Đã cập nhật địa chỉ giao hàng thành công.', 'data' => $shippingAddress], 200);
        }
        catch(\Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try{
            $this->shippingAddressService->delete($id);
            return response()->json(['message' => 'Đã xóa địa chỉ giao hàng thành công.'], 200);
        }
        catch(\Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
