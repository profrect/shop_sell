<?php

namespace App\Http\Requests\V1;

use App\Http\Requests\BaseRequest;

class OrdersRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'order_id'  => 'required|integer',
            'goods'     => 'required|array',
            'goods.id'  => 'required|integer',
            'goods.num' => 'required|integer',
            'username'  => 'required',
            'mobile'    => 'required',
            'address'   => 'required|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'order_id'  => __('fields.order.order_id'),
            'goods'     => __('fields.order.goods'),
            'goods.id'  => __('fields.order.goods_id'),
            'goods.num' => __('fields.order.goods_num'),
            'username'  => __('fields.order.username'),
            'mobile'    => __('fields.order.mobile'),
            'address'   => __('fields.order.address'),
        ];
    }

    /**
     * @var array|array[]
     */
    protected array $scenes = [
        'create' => ['goods', 'username', 'mobile', 'address'],
        'cancel' => ['order_id'],
        'detail' => ['order_id'],
    ];

}
