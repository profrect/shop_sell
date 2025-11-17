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
     * @return bool
     */
    public function create(array $data): bool
    {
        $insert = [];
        foreach ($data['goods'] as $good) {
            $insert[] = [
                'order_id'     => $data['order_id'],
                'goods_id'     => $good['id'],
                'goods_num'    => $good['num'],
                'goods_title'  => $good['title'],
                'goods_price'  => $good['price'],
                'goods_all'    => $good['total'],
                'created_time' => time(),
                'updated_time' => time(),
            ];
        }
        return new OrdersGoods()->insert($insert);
    }


}
