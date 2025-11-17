<?php

namespace App\Http\Services\V1;

use App\Exceptions\ApiException;
use App\Models\MallGoods;
use App\Models\V1\OrderModel;
use App\Repositories\V1\GoodsRepository;
use App\Repositories\V1\OrdersGoodsRepository;
use App\Repositories\V1\OrdersRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class OrdersService
{
    public function __construct(
        protected Request               $request,
        protected OrdersRepository      $ordersRepository,
        protected GoodsRepository       $goodsRepository,
        protected OrdersGoodsRepository $ordersGoodsRepository,
    )
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
            $goodDetail = $this->goodsRepository->detail($good['id']);
            if ($goodDetail === null) throw new ApiException(__('goods.not_exists'));                            // 商品不存在
            if ($goodDetail->stock < $good['num']) throw new ApiException(__('goods.stock_insufficient'));       // 库存不足
            if ($goodDetail->status != MallGoods::STATUS_ENABLE) throw new ApiException(__('goods.not_on_sale'));// 状态异常

            $goods_money         = bcmul($goodDetail->market_price, $good['num'], 2);
            $data['goods'][]     = [
                'id'    => $good['id'],
                'num'   => $good['num'],
                'price' => $goodDetail->market_price,
                'total' => $goods_money,
                'title' => $goodDetail->title,
                'logo'  => $goodDetail->logo,
            ];
            $data['goods_money'] = bcadd($data['goods_money'], $goods_money, 2);
            $data['total_money'] = bcadd($data['total_money'], $goods_money, 2);
        }
        return $data;
    }

    /**
     * @return OrderModel|Model
     * @throws Throwable
     */
    public function create(): Model|OrderModel
    {
        $sure = $this->sure();
        DB::beginTransaction();
        try {
            // 创建订单
            $order = $this->ordersRepository->create();
            // 创建订单商品
            $data = [
                'order_id' => $order->id,
                'goods'    => $sure['goods'],
            ];
            $this->ordersGoodsRepository->create($data);
            // 减库存
            foreach ($sure['goods'] as $good) {
                $this->goodsRepository->reduceStock($good['id'], $good['num']);
            }
            \DB::commit();
            return $order;
        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
    }

    /**
     * 订单详情
     * @param $id
     * @return array
     * @throws ApiException
     */
    public function detail($id): array
    {
        $order = $this->ordersRepository->detail($id);
        if ($order === null) throw new ApiException(__('order.not_exists'));
        return $order?->toArray();
    }

}
