<?php

namespace App\Models\V1;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * @property int $id
 * @property string $number 订单编号
 * @property string $total_money 总金额
 * @property string $goods_money 商品金额
 * @property string $fees_money 快递费用
 * @property int $status 状态：0支付中，1待发货，2已发货，3已收货，4已完成，-1已取消
 * @property int $pay_time 支付时间
 * @property int $send_time 发货时间
 * @property int $receive_time 收货时间
 * @property int $finish_time 完成时间
 * @property string $username 收件人
 * @property string|null $address 收件地址
 * @property int|null $created_time
 * @property int|null $updated_time
 * @property string $create_time
 * @property string $update_time
 * @property string $delete_time
 * @method static Builder|OrderModel newModelQuery()
 * @method static Builder|OrderModel newQuery()
 * @method static Builder|OrderModel query()
 * @method static Builder|OrderModel whereAddress($value)
 * @method static Builder|OrderModel whereCreatedTime($value)
 * @method static Builder|OrderModel whereFeesMoney($value)
 * @method static Builder|OrderModel whereFinishTime($value)
 * @method static Builder|OrderModel whereGoodsMoney($value)
 * @method static Builder|OrderModel whereId($value)
 * @method static Builder|OrderModel whereNumber($value)
 * @method static Builder|OrderModel wherePayTime($value)
 * @method static Builder|OrderModel whereReceiveTime($value)
 * @method static Builder|OrderModel whereSendTime($value)
 * @method static Builder|OrderModel whereStatus($value)
 * @method static Builder|OrderModel whereTotalMoney($value)
 * @method static Builder|OrderModel whereUpdatedTime($value)
 * @method static Builder|OrderModel whereUsername($value)
 * @mixin \Eloquent
 */
class OrderModel extends BaseModel
{
    //状态：0支付中，1待发货，2已发货，3已收货，4已完成，-1已取消
    const int STATUS_PAYING    = 0;
    const int STATUS_WAIT_SEND = 1;
    const int STATUS_SEND      = 2;
    const int STATUS_RECEIVE   = 3;
    const int STATUS_FINISH    = 4;
    const int STATUS_CANCEL    = -1;


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'orders';
    }

    /**
     * 订单商品
     * @return HasMany
     */
    public function getGoods(): HasMany
    {
        return $this->hasMany(OrdersGoods::class, 'order_id', 'id');
    }


}
