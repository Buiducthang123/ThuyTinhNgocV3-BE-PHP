<?php

namespace App\Repositories\ShoppingCart;

use App\Models\ShoppingCart;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;

class ShoppingCartRepository extends BaseRepository implements ShoppingCartRepositoryInterface
{
    public function getModel()
    {
        return ShoppingCart::class;
    }

    public function getCartItems()
    {
        $user = Auth::user();
        if ($user) {
            return $user->productsInCart()->with(['category','discountTiers'])->get();
        }
        return [];
    }

    public function findItemInCart($user_id, $product_id)
    {
        $result = $this->model->where('user_id', $user_id)->where('product_id', $product_id)->first();
        return $result;
    }

    public function delete($product_id)
    {
        $user_id = Auth::id();
        $item = $this->model->where('user_id', $user_id)->where('product_id', $product_id)->first();
        if (!$item) {
            throw new \Exception('Không tìm thấy sản phẩm trong giỏ hàng');
        }
        $item->delete();
        return $item;
    }

}
