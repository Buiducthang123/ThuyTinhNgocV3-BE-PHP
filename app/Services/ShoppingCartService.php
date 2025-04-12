<?php

namespace App\Services;

use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\ShoppingCart\ShoppingCartRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class ShoppingCartService {
    protected $shoppingCartRepository;
    protected $productRepository;

    public function __construct(ShoppingCartRepositoryInterface $shoppingCartRepository, ProductRepositoryInterface $productRepository){

        $this->shoppingCartRepository = $shoppingCartRepository;
        $this->productRepository = $productRepository;
    }

    public function getCartItems(){
        return $this->shoppingCartRepository->getCartItems();
    }

    public function addToCart($data){

        $product_id = $data['product_id'];

        $quantity = $data['quantity'] ?? 1;

        // $checkQuantity = $this->productRepository->checkQuantity($product_id, $quantity);

        // if(!$checkQuantity){
        //     throw new \Exception('Số lượng sản phẩm trong kho không đủ');
        // }

        $user_id = Auth::id();

        $item = $this->shoppingCartRepository->findItemInCart($user_id, $product_id);

        if($item){
            $item->quantity += $quantity;
            $item->save();
            return $item;
        }

        return $this->shoppingCartRepository->create($data);
    }

    public function deleteItem($id){
        return $this->shoppingCartRepository->delete($id);
    }
}
