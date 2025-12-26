<?php

namespace App\Http\Services\V1;

use App\Exceptions\ApiException;
use App\Models\BaseModel;
use App\Models\MallGoods;
use App\Models\V1\ChatUser;
use App\Models\V1\OrderAddress;
use App\Models\V1\OrderModel;
use App\Models\V1\OrdersGoods;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class OrdersService
{
    public function __construct(protected Request $request)
    {
    }

    /**
     * 订单确认
     * @return array
     * @throws ApiException
     */
    public function sure(): array
    {
        $goods = $this->request->input('goods');
        $data  = [
            'goods'       => [],
            'goods_money' => 0,
            'fees_money'  => 0,
            'total_money' => 0,
        ];
        foreach ($goods as $good) {
            $goodDetail = MallGoods::query()->find($good['id']);
            if ($goodDetail === null) throw new ApiException(__('goods.not_exists'));                            // 商品不存在
            if ($goodDetail->status != BaseModel::STATUS_ENABLE) throw new ApiException(__('goods.not_on_sale'));// 状态异常

            $goods_money         = bcmul($goodDetail->discount_price, $good['num'], 2);
            $data['goods'][]     = [
                'id'       => $good['id'],
                'num'      => $good['num'],
                'price'    => $goodDetail->discount_price,
                'total'    => $goods_money,
                'title'    => $goodDetail->title,
                'logo'     => $goodDetail->logo,
                'logo_url' => $goodDetail->logo_url,
            ];
            $data['goods_money'] = bcadd($data['goods_money'], $goods_money, 2);
            $data['total_money'] = bcadd($data['total_money'], $goods_money, 2);
        }
        return $data;
    }

    /**
     * @param ChatUser $chatUser
     * @param array $params
     * @return array
     * @throws ApiException
     */
    public function create(ChatUser $chatUser, array $params = []): array
    {
        $nowTime             = time();
        $res                 = $this->sure();
        $order               = new OrderModel();
        $order->user_id      = $chatUser->id;
        $order->number       = orderNumber();
        $order->status       = OrderModel::STATUS_PAYING;
        $order->goods_money  = $res['goods_money'];
        $order->total_money  = $res['total_money'];
        $order->fees_money   = $res['fees_money'];
        $order->expired_time = $nowTime + 60 * 30;
        $order->save();
        $insert = [];
        foreach ($res['goods'] as $good) {
            $insert[] = [
                'order_id'     => $order->id,
                'goods_id'     => $good['id'],
                'goods_num'    => $good['num'],
                'goods_price'  => $good['price'],
                'goods_all'    => $good['total'],
                'goods_title'  => $good['title'],
                'goods_logo'   => $good['logo'],
                'created_time' => $nowTime,
                'updated_time' => $nowTime,
            ];
        }
        OrdersGoods::query()->insert($insert);
        // 添加收货地址
        $address              = new OrderAddress();
        $address->order_id    = $order->id;
        $address->phone       = $params['phone'];
        $address->address     = $params['address'];
        $address->first_name  = $params['first_name'];
        $address->last_name   = $params['last_name'];
        $address->country     = $params['country'];
        $address->city        = $params['city'];
        $address->email       = $params['email'];
        $address->zip_code    = $params['zip_code'] ?? '';
        $address->room        = $params['room'] ?? '';
        $address->agree_email = (bool)$params['agree_email'] ?? false;
        $address->agree_info  = (bool)$params['agree_info'] ?? false;
        $address->create_time = $nowTime;
        $address->update_time = $nowTime;
        $address->save();

        return $order->toArray();
    }

    /**
     * 订单详情
     * @param $id
     * @return array
     * @throws ApiException
     */
    public function detail($id): array
    {
        $order = OrderModel::query()->with(['goods', 'orderAddress'])->find($id);
        if ($order === null) throw new ApiException(__('order.not_exists'));
        return $order->toArray();
    }

}
