<?php

use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\MomoPaymentController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function() {
    Route::post('register', [UserController::class, 'register']);
    Route::post('login', [UserController::class, 'login']);
    
    // Thêm route mới cho get user
    Route::middleware('auth:sanctum')->get('user', [UserController::class, 'getCurrentUser']);
});

Route::prefix('auth')->middleware('auth:sanctum')->group(function() {
    Route::post('logout', [UserController::class, 'logout']);
    Route::put('profile', [UserController::class, 'updateProfile']);
    Route::post('/profile', [UserController::class, 'updateProfile']);
    Route::put('/password', [UserController::class, 'changePassword']);
});
Route::get('products', [ProductController::class,'index']); 
Route::get('products/{category}/category', [ProductController::class,'filterProductByCategory']); 
Route::get('products/{brand}/brand', [ProductController::class,'filterProductByBrand']); 
Route::get('products/{color}/color', [ProductController::class,'filterProductByColor']); 
Route::get('products/{size}/size', [ProductController::class,'filterProductBySize']); 
Route::get('products/{searchTerm}/find', [ProductController::class,'findProductByTerm']); 

Route::get('product/{product}/show', [ProductController::class,'show']); 
// Lấy các đánh giá của sản phẩm
Route::middleware('auth:sanctum')->group(function() {
Route::post('store/review',[ReviewController::class,'store']);
// Route::put('update/review',[ReviewController::class,'update']);
// Route::post('delete/review',[ReviewController::class,'delete']);
Route::put('/update/review/{id}', [ReviewController::class, 'update']);
Route::delete('/delete/review/{id}', [ReviewController::class, 'delete']);

});
// In your api.php routes file
// Route::post('coupons/validate', [CouponController::class, 'validateCoupon']);
// Route::get('/coupons/check-usage', [CouponController::class, 'checkUserUsage']);
Route::post('coupons/validate', [CouponController::class, 'validateCoupon']);
Route::get('coupons/check-usage', [CouponController::class, 'checkUserUsage']);

Route::middleware('auth:sanctum')->group(function () {
    // COD Orders
    Route::post('/orders/cod', [OrderController::class, 'createCODOrder']);
    
    // Order Status
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/status', [OrderController::class, 'checkStatus']);
    
    // User Orders
    Route::get('/user/orders', [OrderController::class, 'userOrders']);
    Route::get('/user/orders/{order}', [OrderController::class, 'userOrderDetail']);
    Route::post('/user/orders/{order}/cancel', [OrderController::class, 'cancel'])
    ;
});