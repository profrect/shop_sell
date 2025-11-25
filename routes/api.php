<?php

use App\Http\Controllers\api\FileController;
use App\Http\Controllers\api\GoodsController;
use App\Http\Controllers\api\OrderController;
use App\Http\Controllers\api\WalletAddressController;
use Illuminate\Support\Facades\Route;

Route::prefix('goods')->group(function () {
    Route::get('', [GoodsController::class, 'list'])->name('商品列表');
});
// 订单
Route::prefix('orders')->group(function () {
    Route::post('sure', [OrderController::class, 'sure'])->name('确认订单');
    Route::get('detail/{order_id}', [OrderController::class, 'detail'])->name('订单详情');
});
// 附件
Route::prefix('file')->group(function () {
    Route::get('', [FileController::class, 'list'])->name('图片列表');
});
//钱包地址
Route::prefix('wallet')->group(function () {
    Route::get('', [WalletAddressController::class, 'index'])->name('钱包列表');
});


