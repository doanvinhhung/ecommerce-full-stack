<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/',[AdminController::class,'login'])->name('admin.login');
Route::post('admin/auth',[AdminController::class,'auth'])->name('admin.auth');


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['admin']], function () {
    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->middleware(['auth', 'verified'])->name('dashboard');
    
Route::get('/dashboard',[AdminController::class,'index'])->name('index');
Route::post('/logout',[AdminController::class,'logout'])->name('logout');
Route::resource('categories', CategoryController::class);
Route::resource('brands', BrandController::class);
Route::resource('colors', ColorController::class);
Route::resource('sizes', SizeController::class);
Route::resource('products', ProductController::class); 

Route::resource('coupons', CouponController::class);
// Quản lý đơn hàng
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::post('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
Route::post('/orders/{order}/update-payment-status', [OrderController::class, 'updatePaymentStatus'])->name('orders.update-payment-status');
Route::post('/orders/{order}/update-notes', [OrderController::class, 'updateNotes'])->name('orders.update-notes');
// Route::get('toggle-news-status', [CouponController::class, 'statusToggle'])->name('toggle-news-status');
// Route::resource('categories', CategoryController::class, [
//     'names' => [
//         'index' => 'admin.categories.index',
//         'create' => 'admin.categories.create',
//         'store' => 'admin.categories.store',
//         'edit' => 'admin.categories.edit',
//         'update' => 'admin.categories.update',
//         'destroy' => 'admin.categories.destroy',
//     ]
// ]);


});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
