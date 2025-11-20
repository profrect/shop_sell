<?php

use App\Http\Controllers\api\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('goods')->group(function () {

});
// 订单
Route::prefix('orders')->group(function () {
    Route::put('', [OrderController::class, 'create'])->name('创建订单');
    Route::post('sure', [OrderController::class, 'sure'])->name('确认订单');
    Route::get('detail/{order_id}', [OrderController::class, 'detail'])->name('订单详情');
});


