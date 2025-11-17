<?php

namespace App\Repositories\V1;

use App\Models\V1\OrderModel;
use App\Models\V1\OrdersGoods;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OrdersGoodsRepository extends BaseRepository
{

    /**
     * 创建订单商品
     * @param array $data
     * @return Model|OrdersGoods
     */
    public function create(array $data): Model|OrdersGoods
    {
        return OrdersGoods::create(
            [
                'order_id'     => $data['order_id'],
                'goods_id'     => $data['goods_id'],
                'goods_num'    => $data['goods_num'],
                'goods_title'  => $data['goods_title'],
                'goods_price'  => $data['goods_price'],
                'goods_all'    => $data['goods_all'],
                'created_time' => time(),
                'updated_time' => time(),
            ]
        );
    }


}
