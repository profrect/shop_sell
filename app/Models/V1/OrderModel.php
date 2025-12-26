<?php

namespace App\Models\V1;

use App\Exceptions\ApiException;
use App\Models\BaseModel;
use App\Models\MallGoods;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;


/**
 * @property int $id
 * @property int|null $user_id 用户id
 * @property string $number 订单编号
 * @property string $total_money 总金额
 * @property string $goods_money 商品金额
 * @property string $fees_money 快递费用
 * @property int $status 状态：0支付中，1待发货，2已发货，3已收货，4已完成，-1已取消
 * @property int $pay_time 支付时间
 * @property int $expired_time 过期时间
 * @property int $send_time 发货时间
 * @property int $receive_time 收货时间
 * @property int $finish_time 完成时间
 * @property string $username 收件人
 * @property string|null $mobile 收件人联系方式
 * @property string|null $address 收件地址
 * @property int|null $created_time
 * @property int|null $updated_time
 * @property string $create_time
 * @property string $update_time
 * @property string $delete_time
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\V1\OrdersGoods> $goods
 * @property-read int|null $goods_count
 * @property-read \App\Models\V1\OrderAddress|null $orderAddress
 * @method static Builder|OrderModel newModelQuery()
 * @method static Builder|OrderModel newQuery()
 * @method static Builder|OrderModel query()
 * @method static Builder|OrderModel whereAddress($value)
 * @method static Builder|OrderModel whereCreatedTime($value)
 * @method static Builder|OrderModel whereExpiredTime($value)
 * @method static Builder|OrderModel whereFeesMoney($value)
 * @method static Builder|OrderModel whereFinishTime($value)
 * @method static Builder|OrderModel whereGoodsMoney($value)
 * @method static Builder|OrderModel whereId($value)
 * @method static Builder|OrderModel whereMobile($value)
 * @method static Builder|OrderModel whereNumber($value)
 * @method static Builder|OrderModel wherePayTime($value)
 * @method static Builder|OrderModel whereReceiveTime($value)
 * @method static Builder|OrderModel whereSendTime($value)
 * @method static Builder|OrderModel whereStatus($value)
 * @method static Builder|OrderModel whereTotalMoney($value)
 * @method static Builder|OrderModel whereUpdatedTime($value)
 * @method static Builder|OrderModel whereUserId($value)
 * @method static Builder|OrderModel whereUsername($value)
 * @mixin \Eloquent
 */
class OrderModel extends BaseModel
{
    //状态：0支付中，1待发货，2已发货，3已收货，4已完成，-1已取消
    const  STATUS_PAYING    = 0;
    const  STATUS_WAIT_SEND = 1;
    const  STATUS_SEND      = 2;
    const  STATUS_RECEIVE   = 3;
    const  STATUS_FINISH    = 4;
    const  STATUS_CANCEL    = -1;


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'orders';
    }

    /**
     * 订单商品
     * @return HasMany
     */
    public function goods(): HasMany
    {
        return $this->hasMany(OrdersGoods::class, 'order_id', 'id');
    }

    /**
     * 订单地址
     * @return HasOne
     */
    public function orderAddress(): HasOne
    {
        return $this->hasOne(OrderAddress::class, 'order_id', 'id');
    }

    /**
     *支付成功回调
     * @param $order_id
     * @return true
     * @throws ApiException
     */
    public static function pay_notify($order_id): true
    {
        $row = self::where('id', $order_id)->first();
        if (!$row) {
            throw new ApiException("订单不存在");
        }
        if ($row['status'] != self::STATUS_PAYING) {
            throw new ApiException("订单已付款成功");
        }
        $row->status   = self::STATUS_WAIT_SEND;
        $row->pay_time = time();
        $row->save();
        return true;
    }


}
