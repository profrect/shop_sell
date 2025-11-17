<?php

namespace App\Repositories\V1;

use App\Models\V1\OrderModel;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class OrderRepository extends BaseRepository
{

    //增删改查
    public function create(array $data): Model|OrderModel
    {
        return OrderModel::create(
            [
                'order_id'          => $data['order_id'],
                'user_id'           => $data['user_id'],
                'goods_id'          => $data['goods_id'],
                'goods_num'         => $data['goods_num'],
                'goods_price'       => $data['goods_price'],
                'goods_total_price' => $data['goods_total_price'],
                'order_status'      => $data['order_status'],
                'pay_status'        => $data['pay_status'],
            ]
        );
    }

}
