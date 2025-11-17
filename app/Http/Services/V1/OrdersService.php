<?php

namespace App\Http\Services\V1;

use App\Models\V1\OrderModel;
use App\Repositories\V1\OrdersRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class OrdersService
{
    public function __construct(protected Request $request, protected OrdersRepository $ordersRepository){}

    public function sure()
    {

    }

    /**
     * @return OrderModel|Model
     * @throws Throwable
     */
    public function create(): Model|OrderModel
    {
        DB::beginTransaction();
        try {

            // 创建订单
            $order = $this->ordersRepository->create();
            // 创建订单商品

            \DB::commit();
            return $order;
        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
    }

}
