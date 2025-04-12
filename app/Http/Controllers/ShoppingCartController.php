<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShoppingCartRequest;
use App\Services\ShoppingCartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShoppingCartController extends Controller
{
    protected $shoppingCartService;

    public function __construct(ShoppingCartService $shoppingCartService)
    {
        $this->shoppingCartService = $shoppingCartService;
    }

    public function getCartItems()
    {
        $cartItems = $this->shoppingCartService->getCartItems();
        return response()->json($cartItems);
    }

    public function create(ShoppingCartRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::id();
        try {

            $this->shoppingCartService->addToCart($data);
            return response()->json(['message' => 'Thêm vào giỏ hàng thành công']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function delete($product_id)
    {
        try {
            $this->shoppingCartService->deleteItem($product_id);
            return response()->json(['message' => 'Xóa sản phẩm khỏi giỏ hàng thành công']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

}
