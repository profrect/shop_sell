<?php

namespace App\Repositories\V1;

use App\Models\V1\OrderModel;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OrdersRepository extends BaseRepository
{

    //增删改查
    public function create(): Model|OrderModel
    {
        return OrderModel::create(
            [
                'number'       => orderNumber(),
                'total_money'  => $this->params['total_money'],
                'goods_money'  => $this->params['goods_money'],
                'fees_money'   => $this->params['fees_money'] ?? 0,
                'status'       => OrderModel::STATUS_PAYING,
                'username'     => $this->params['username'],
                'address'      => $this->params['address'],
                'mobile'       => $this->params['mobile'],
                'created_time' => time(),
                'updated_time' => time(),
            ]
        );
    }

    private function sqlQuery(): Builder|OrderModel
    {
        $query = OrderModel::query();
        if (isset($this->params['status'])) {
            $query->where('status', $this->params['status']);
        }
        if (isset($this->params['username'])) {
            $query->where('username', $this->params['username']);
        }
        if (isset($this->params['number'])) {
            $query->where('number', $this->params['number']);
        }
        if (isset($this->params['mobile'])) {
            $query->where('mobile', $this->params['mobile']);
        }
        if (isset($this->params['start_time'])) {
            $query->whereTime('created_time', '>=', $this->params['start_time']);
        }
        if (isset($this->params['end_time'])) {
            $query->whereTime('created_time', '<=', $this->params['end_time']);
        }
        return $query;
    }

    /**
     * 获取列表
     * @param array $params
     * @return array
     */
    public function getList(): array
    {
        if (!empty($this->params['page'])) {
            return $this->sqlQuery()->paginate($this->params['limit'] ?? 10)->toArray();
        } else {
            return $this->sqlQuery()->get()->toArray();
        }
    }

    /**
     * 取消订单
     * @param $id
     * @return bool|int
     */
    public function cancel($id): bool|int
    {
        return OrderModel::query()->where('id', $id)->update(['status' => OrderModel::STATUS_CANCEL]);
    }

    /**
     * 订单支付
     * @param $id
     * @return bool|int
     */
    public function pay($id): bool|int
    {
        return OrderModel::query()->where('id', $id)->update(
            [
                'status'   => OrderModel::STATUS_PAYING,
                'pay_time' => time(),
            ]
        );
    }

    /**
     * 订单发货
     * @param $id
     * @return bool|int
     */
    public function send($id): bool|int
    {
        return OrderModel::query()->where('id', $id)->update(
            [
                'status'    => OrderModel::STATUS_SEND,
                'send_time' => time(),
            ]
        );
    }

    /**
     * 订单收货
     * @param $id
     * @return bool|int
     */
    public function receive($id): bool|int
    {
        return OrderModel::query()->where('id', $id)->update(
            [
                'status'       => OrderModel::STATUS_RECEIVE,
                'receive_time' => time(),
            ]
        );
    }

    /**
     * 订单完成
     * @param $id
     * @return bool|int
     */
    public function finish($id): bool|int
    {
        return OrderModel::query()->where('id', $id)->update(
            [
                'status'      => OrderModel::STATUS_FINISH,
                'finish_time' => time(),
            ]
        );
    }

}
