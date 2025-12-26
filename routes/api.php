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
    Route::get('detail/{id}', [GoodsController::class, 'detail'])->name('商品详情');
});
// 订单
Route::prefix('orders')->group(function () {
    Route::post('sure', [OrderController::class, 'sure'])->name('确认订单');
    Route::post('create', [OrderController::class, 'create'])->name('创建订单');
    Route::get('detail/{order_id}', [OrderController::class, 'detail'])->name('订单详情');
    Route::any('payNotify', [OrderController::class, 'payNotify'])->middleware('redis.lock:payNotify:{orderNo},15')->name('etPay支付回调');
    Route::post('sfPayNotify', [OrderController::class, 'sfPayNotify'])->middleware('redis.lock:sfPayNotify:{merchant_order_sn},15')->name('sfPay法币支付回调');
    Route::post('digitalNotify', [OrderController::class, 'sfPayDigitalNotify'])->middleware('redis.lock:digitalNotify:{merchant_order_sn},15')->name('sfPay数字货币支付回调');
});
// 文案
Route::prefix('set')->group(function () {
    Route::get('', [FileController::class, 'list'])->name('图片列表');
});
//钱包地址
Route::prefix('wallet')->group(function () {
    Route::get('option', [WalletAddressController::class, 'option'])->name('支付类型');
    Route::post('pay', [WalletAddressController::class, 'pay'])->name('唤起支付');
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

