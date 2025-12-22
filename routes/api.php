<?php

use App\Http\Controllers\api\CountryController;
use App\Http\Controllers\api\FileController;
use App\Http\Controllers\api\GoodsController;
use App\Http\Controllers\api\IconController;
use App\Http\Controllers\api\OrderController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\WalletAddressController;
use Illuminate\Support\Facades\Route;

//商品
Route::prefix('goods')->group(function () {
    Route::get('', [GoodsController::class, 'list'])->name('商品列表');
});
// 订单
Route::prefix('orders')->group(function () {
    Route::post('sure', [OrderController::class, 'sure'])->name('确认订单');
    Route::post('create', [OrderController::class, 'create'])->name('创建订单');
    Route::get('detail/{order_id}', [OrderController::class, 'detail'])->name('订单详情');
    Route::any('payNotify', [OrderController::class, 'payNotify'])->name('支付回调');
});
// 附件
Route::prefix('file')->group(function () {
    Route::get('', [FileController::class, 'list'])->name('图片列表');
});
//钱包地址
Route::prefix('wallet')->group(function () {
    Route::get('', [WalletAddressController::class, 'index'])->name('钱包列表');
});
//图标
Route::prefix('icon')->group(function () {
    Route::get('', [IconController::class, 'index'])->name('图标列表');
});
// 用户
Route::prefix('user')->group(function () {
    Route::post('getSign', [UserController::class, 'getSign'])->name('获取IM签名');
});
// 国家
Route::prefix('country')->group(function () {
    Route::get('', [CountryController::class, 'list'])->name('获取国家列表');
});

