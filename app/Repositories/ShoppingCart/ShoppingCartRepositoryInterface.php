<?php
namespace App\Repositories\ShoppingCart;

use App\Repositories\RepositoryInterface;

interface ShoppingCartRepositoryInterface extends RepositoryInterface{

    public function getCartItems();

    public function findItemInCart($user_id, $product_id);

    public function delete($product_id);


}
