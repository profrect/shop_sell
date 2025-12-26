<?php

namespace App\Models\V1;

use App\Exceptions\ApiException;
use App\Models\BaseModel;
use App\Models\MallGoods;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;


/**
 * @property int $id
 * @property int $order_id 订单id
 * @property string $amount 订单金额
 * @property string $fee_amount 手续费
 * @property string $currency 货币类型
 * @property string $pay_channel 支付渠道
 * @property string $pay_type 支付方式：alipay，wechat,usdt
 * @property int $pay_time 支付时间
 * @property int $status 状态：0-待支付、1-成功、2-失败、6-过期、9-驳回
 * @property string|null $order_sn 支付订单号
 * @property string|null $response 同步回调数据
 * @property string|null $notify_response 异步回调数据
 * @property string $create_time 创建时间
 * @property string $update_time 更新时间
 * @property string $delete_time
 * @method static Builder|OrdersPay newModelQuery()
 * @method static Builder|OrdersPay newQuery()
 * @method static Builder|OrdersPay query()
 * @method static Builder|OrdersPay whereAmount($value)
 * @method static Builder|OrdersPay whereCreateTime($value)
 * @method static Builder|OrdersPay whereCurrency($value)
 * @method static Builder|OrdersPay whereFeeAmount($value)
 * @method static Builder|OrdersPay whereId($value)
 * @method static Builder|OrdersPay whereNotifyResponse($value)
 * @method static Builder|OrdersPay whereOrderId($value)
 * @method static Builder|OrdersPay whereOrderSn($value)
 * @method static Builder|OrdersPay wherePayChannel($value)
 * @method static Builder|OrdersPay wherePayTime($value)
 * @method static Builder|OrdersPay wherePayType($value)
 * @method static Builder|OrdersPay whereResponse($value)
 * @method static Builder|OrdersPay whereStatus($value)
 * @method static Builder|OrdersPay whereUpdateTime($value)
 * @mixin \Eloquent
 */
class OrdersPay extends BaseModel
{


    const PAY_TYPE_WECHAT = 'wechat';
    const PAY_TYPE_ALIPAY = 'alipay';
    const PAY_TYPE_USDT   = 'usdt';


    const CURRENCY_USD = 'USD';
    const CURRENCY_CNY = 'CNY';
    const CURRENCY_USDT = 'USDT';


    // 状态：0-待支付、1-成功、2-失败、6-过期、9-驳回
    const PAY_STATUS_WAIT = 0;
    const PAY_STATUS_SUCCESS = 1;
    const PAY_STATUS_FAIL = 2;
    const PAY_STATUS_EXPIRE = 6;
    const PAY_STATUS_REJECT = 9;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'orders_pay';
    }

    /**
     * 添加支付日志
     * @param OrderModel $order
     * @param string $pay_type
     * @param string $currency
     * @param string $pay_channel
     * @param float $fee
     * @return OrdersPay
     */
    public static function addLog(OrderModel $order, string $pay_type = self::PAY_TYPE_ALIPAY, string $currency = self::CURRENCY_USD, string $pay_channel = 'sfPay', float $fee = 0): OrdersPay
    {
        $pay_log              = new self();
        $pay_log->order_id    = $order->id;
        $pay_log->pay_type    = $pay_type;
        $pay_log->pay_channel = $pay_channel;
        $pay_log->fee_amount  = $fee;
        $pay_log->amount      = $order->total_money;
        $pay_log->currency    = $currency;
        $pay_log->status      = self::PAY_STATUS_WAIT;
        $pay_log->save();
        return $pay_log;
    }



}
