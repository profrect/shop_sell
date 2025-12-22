<?php

namespace App\Http\Requests\V1;

use App\Http\Requests\BaseRequest;

class OrdersRequest extends BaseRequest
{
    public function fullRules(): array
    {
        return [
            'order_id'    => 'required|integer',
            'goods'       => 'required|array',
            'goods.*.id'  => 'required|integer',
            'goods.*.num' => 'required|integer',
            'first_name'  => 'required',
            'last_name'   => 'required',
            'address'     => 'required|string',
            'email'       => 'required|email',
            'country'     => 'required|string',
            'city'        => 'required|string',
            'zipcode'     => 'nullable|string',
            'phone'       => 'required',
            'room'        => 'nullable|string',
        ];
    }

    /**
     * 方法名到场景名的映射
     * @return array
     */
    protected function methodSceneMap(): array
    {
        return [
            'sure'   => 'sure',
            'create' => 'create',
            'cancel' => 'cancel',
            'detail' => 'detail',
        ];
    }

    public function attributes(): array
    {
        return [
            'order_id'    => __('fields.order.order_id'),
            'goods'       => __('fields.order.goods'),
            'goods.*.id'  => __('fields.order.goods_id'),
            'goods.*.num' => __('fields.order.goods_num'),
            'address'     => __('fields.order.address'),
            'email'       => __('fields.order.email'),
            'country'     => __('fields.order.country'),
            'city'        => __('fields.order.city'),
            'zipcode'     => __('fields.order.zipcode'),
            'phone'       => __('fields.order.phone'),
            'room'        => __('fields.order.room'),
            'first_name'  => __('fields.order.first_name'),
            'last_name'   => __('fields.order.last_name'),
        ];
    }

    /**
     * @var array|array[]
     */
    protected array $scenes = [
        'sure'   => ['goods'],
        'create' => ['goods', 'address', 'email', 'country', 'city', 'phone', 'first_name', 'last_name', 'zipcode', 'room'],
        'cancel' => ['order_id'],
        'detail' => ['order_id'],
    ];

}
