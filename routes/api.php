<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductTransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DiscountTiersController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ShippingAddressController;
use App\Http\Controllers\ShoppingCartController;
use App\Http\Controllers\StatisticalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifyEmailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Auth Routes
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
Route::get('/user-me', [AuthController::class, 'user'])->middleware('auth:sanctum')->name('user');

// Verify Email
// Route::post('/send-email-verification-notification', [VerifyEmailController::class, 'sendEmailVerificationNotification'])->middleware('auth:sanctum')->name('send-email-verification-notification');
Route::get('/email/verify', [VerifyEmailController::class, 'verifyEmail'])->middleware(['signed'])->name('verification.verify');

// Social Login
Route::get('/login/google', [AuthController::class, 'loginGoogle'])->name('login.google');
Route::get('/auth/google/process', [AuthController::class, 'loginGoogleCallback'])->name('login.google.callback');

//User Routes
Route::patch('/user/update-me', [UserController::class, 'updateMe'])->middleware('auth:sanctum')->name('user.update-me');
Route::get('/users', [UserController::class, 'index'])->middleware(['auth:sanctum','admin'])->name('users');
Route::get('/user/{id}', [UserController::class, 'show'])->middleware(['auth:sanctum','admin'])->name('user.show');
Route::patch('/user/{userId}', [UserController::class, 'update'])->middleware(['auth:sanctum','admin'])->name('user.update');
Route::delete('/user/{id}', [UserController::class, 'destroy'])->middleware(['auth:sanctum','admin'])->name('user.delete');

//Role Routes
Route::get('/roles', [RoleController::class, 'getAll'])->middleware(['auth:sanctum','admin'])->name('roles');


//Product Routes
Route::prefix('products')->middleware(['auth:sanctum','admin'])->group(function(){
    Route::post('/', [ProductController::class, 'create'])->name('products.create');
    Route::patch('/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/{id}', [ProductController::class, 'delete'])->name('products.delete');
});

Route::get('products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('product-by-category/{category_id}', [ProductController::class, 'getProductByCategory'])->name('products.by-category');
Route::get('/products', [ProductController::class, 'index'])->name('products.all');


//Shipping Address Routes
Route::prefix('shipping-addresses')->middleware('auth:sanctum')->group(function(){
    Route::post('/', [ShippingAddressController::class, 'create'])->name('shipping-addresses.create');
    Route::patch('/{id}', [ShippingAddressController::class, 'update'])->name('shipping-addresses.update');
    Route::delete('/{id}', [ShippingAddressController::class, 'delete'])->name('shipping-addresses.delete');
});


//category routes
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.all');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::prefix('/categories')->middleware(['auth:sanctum','admin'])->group(function(){
    Route::post('/', [CategoryController::class, 'create'])->name('categories.create');
    Route::patch('/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/{id}', [CategoryController::class, 'delete'])->name('categories.delete');
});


//ProductTransaction Routes
Route::prefix('product-transactions')->middleware(['auth:sanctum','admin'])->group(function(){
    Route::get('/', [ProductTransactionController::class, 'index'])->name('product-transactions.all');
    Route::get('/{id}', [ProductTransactionController::class, 'show'])->name('product-transactions.show');
    Route::post('/', [ProductTransactionController::class, 'create'])->name('product-transactions.create');
    Route::patch('/{id}', [ProductTransactionController::class, 'update'])->name('product-transactions.update');
    Route::delete('/{id}', [ProductTransactionController::class, 'delete'])->name('product-transactions.delete');
});

//Shopping Cart Routes
Route::prefix('shopping-carts')->middleware('auth:sanctum')->group(function(){
    Route::get('/', [ShoppingCartController::class, 'getCartItems'])->name('shopping-carts.my-cart');
    Route::post('/', [ShoppingCartController::class, 'create'])->name('shopping-carts.add-to-cart');
    Route::delete('/{id}', [ShoppingCartController::class, 'delete'])->name('shopping-carts.delete-item');

});

//Payment Routes

Route::prefix('payments')->middleware(['auth:sanctum','admin'])->group(function(){
    // Route::post('/', [PaymentController::class, 'create'])->name('payments.create');
    // Route::get('/{id}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('/', [PaymentController::class, 'getAll'])->name('payments.all');
});
Route::get('my-payments', [PaymentController::class, 'getMyPayment'])->middleware('auth:sanctum')->name('payments.my-payments');



//Order Routes
Route::post('/orders', [OrderController::class, 'create'])->middleware('auth:sanctum')->name('orders.create');
Route::get('/order/{id}/payment-return', [OrderController::class, 'updateStatusAfterPayment'])->name('orders.update-status-after-payment');
Route::get('/orders', [OrderController::class, 'getAll'])->middleware(['auth:sanctum','admin'])->name('orders.all');
Route::get('/order/{id}', [OrderController::class, 'show'])->middleware(['auth:sanctum','admin'])->name('orders.show');
Route::patch('/order/{id}', [OrderController::class, 'update'])->middleware(['auth:sanctum','admin'])->name('orders.update');
Route::get('/my-orders', [OrderController::class, 'getMyOrder'])->middleware('auth:sanctum')->name('orders.my-orders');
Route::post('/order/{id}/cancel', [OrderController::class, 'cancelOrder'])->middleware('auth:sanctum')->name('orders.cancel-order');

//Promotion Routes
Route::prefix('promotions')->middleware(['auth:sanctum','admin'])->group(function(){
    Route::post('/', [PromotionController::class, 'create'])->name('promotions.create');
    Route::patch('/{id}', [PromotionController::class, 'update'])->name('promotions.update');
    Route::delete('/{id}', [PromotionController::class, 'delete'])->name('promotions.delete');
});
Route::get('promotions', [PromotionController::class,'all'])->name('promotions.all');
Route::get('promotions/{id}', [PromotionController::class,'show'])->name('promotions.show');


// Discount Tiers Routes
Route::prefix('discount-tiers')->middleware(['auth:sanctum','admin'])->group(function(){
    Route::post('/', [DiscountTiersController::class, 'create'])->name('discount-tiers.create');
    //createMany
    Route::post('/create-many', [DiscountTiersController::class, 'createMany'])->name('discount-tiers.create-many');
    Route::patch('/{id}', [DiscountTiersController::class, 'update'])->name('discount-tiers.update');
    Route::delete('/{id}', [DiscountTiersController::class, 'delete'])->name('discount-tiers.delete');
});


//Statistics Routes
Route::get('/statistics', [StatisticalController::class, 'index'])->middleware(['auth:sanctum','admin'])->name('statistics');
Route::get('/statistics/revenue', [StatisticalController::class, 'getRevenueByTime'])->middleware(['auth:sanctum','admin'])->name('statistics.revenue');
Route::get('/statistics/orders', [StatisticalController::class, 'getOrdersByTime'])->middleware(['auth:sanctum','admin'])->name('statistics.orders');
Route::get('/statistics/top-10-best-seller', [StatisticalController::class, 'getTop10BestSeller'])->middleware(['auth:sanctum','admin'])->name('statistics.top-10-best-seller');
Route::get('/statistics/top-10-customer', [StatisticalController::class, 'getTop10Customer'])->middleware(['auth:sanctum','admin'])->name('statistics.top-10-customer');

//Review Routes
Route::get('/reviews', [ReviewController::class, 'getAll'])->middleware(['auth:sanctum','admin'])->name('reviews.all');
Route::post('/reviews', [ReviewController::class, 'create'])->middleware('auth:sanctum')->name('reviews.create');
Route::patch('/reviews/{id}', [ReviewController::class, 'update'])->middleware(['auth:sanctum','admin'])->name('reviews.update');
Route::get('/reviews/product/{product_id}', [ReviewController::class, 'showByProduct'])->name('reviews.show-by-product');
