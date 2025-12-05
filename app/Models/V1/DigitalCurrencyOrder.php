<?php

namespace App\Models\V1;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id 用户ID
 * @property int $order_id 订单id
 * @property string $currency_type 币种类型
 * @property string $protocol_type 协议类型
 * @property string $amount 金额
 * @property string|null $orderNo 支付平台订单号
 * @property int $orderType 订单类型：1充币,2提币
 * @property int $addressType 地址类型：1内部地址,2外部地址
 * @property string|null $toAddress 目标地址
 * @property string|null $fromAddress 来源地址
 * @property string|null $remark 备注
 * @property string|null $txid 哈希
 * @property string|null $networkFee 网络费用
 * @property string|null $status 状态 waiting打包中,pending发送中,confirming确认中,cantrade可交易,success已完成,faild失败
 * @property int $refund_status 退款状态 0无 1待退款 2已退款
 * @property int $notifyTime 通知时间戳单位秒
 * @property int $createTime 创建时间10位时间戳
 * @property string|null $return_data 同步回调内容
 * @property string|null $notify_data 异步回调内容
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $type 类型：1订单提现 2余额提现
 * @property string $create_time
 * @property string $update_time
 * @property string $delete_time
 * @method static Builder|DigitalCurrencyOrder newModelQuery()
 * @method static Builder|DigitalCurrencyOrder newQuery()
 * @method static Builder|DigitalCurrencyOrder query()
 * @method static Builder|DigitalCurrencyOrder whereAddressType($value)
 * @method static Builder|DigitalCurrencyOrder whereAmount($value)
 * @method static Builder|DigitalCurrencyOrder whereCreateTime($value)
 * @method static Builder|DigitalCurrencyOrder whereCreatedAt($value)
 * @method static Builder|DigitalCurrencyOrder whereCurrencyType($value)
 * @method static Builder|DigitalCurrencyOrder whereFromAddress($value)
 * @method static Builder|DigitalCurrencyOrder whereId($value)
 * @method static Builder|DigitalCurrencyOrder whereNetworkFee($value)
 * @method static Builder|DigitalCurrencyOrder whereNotifyData($value)
 * @method static Builder|DigitalCurrencyOrder whereNotifyTime($value)
 * @method static Builder|DigitalCurrencyOrder whereOrderId($value)
 * @method static Builder|DigitalCurrencyOrder whereOrderNo($value)
 * @method static Builder|DigitalCurrencyOrder whereOrderType($value)
 * @method static Builder|DigitalCurrencyOrder whereProtocolType($value)
 * @method static Builder|DigitalCurrencyOrder whereRefundStatus($value)
 * @method static Builder|DigitalCurrencyOrder whereRemark($value)
 * @method static Builder|DigitalCurrencyOrder whereReturnData($value)
 * @method static Builder|DigitalCurrencyOrder whereStatus($value)
 * @method static Builder|DigitalCurrencyOrder whereToAddress($value)
 * @method static Builder|DigitalCurrencyOrder whereTxid($value)
 * @method static Builder|DigitalCurrencyOrder whereType($value)
 * @method static Builder|DigitalCurrencyOrder whereUpdatedAt($value)
 * @method static Builder|DigitalCurrencyOrder whereUserId($value)
 * @mixin \Eloquent
 */
class DigitalCurrencyOrder extends BaseModel
{
    protected $table   = 'digital_currency_order';
    protected $guarded = [];


    /**
     * 添加虚拟订单记录
     * @param $user
     * @param $order_id
     * @param $params
     * @param int $type
     * @return DigitalCurrencyOrder
     */
    public static function addLog($user, $order_id, $params, int $type = 0): DigitalCurrencyOrder
    {
        $order = new self();
        $order->forceFill(
            [
                'user_id'       => $user['id'],
                'order_id'      => $order_id,
                'orderNo'       => $params['orderNo'],
                'currency_type' => $params['currency_type'],
                'protocol_type' => $params['protocol_type'],
                'amount'        => $params['orderType'] == 2 ? $params['number'] : $params['amount'],
                'orderType'     => $params['orderType'],
                'addressType'   => $params['addressType'],
                'toAddress'     => $params['toAddress'],
                'fromAddress'   => $params['fromAddress'],
                'remark'        => $params['remark'],
                'txid'          => $params['txid'] ?? '',
                'networkFee'    => $params['networkFee'] ?? '',
                'status'        => $params['status'],
                'refund_status' => $params['refund_status'] ?? 0,
                'notifyTime'    => $params['notifyTime'] ?? 0,
                'createTime'    => $params['createTime'] ?? 0,
                'return_data'   => $params['orderType'] == 1 ? json_encode($params) : '',
                'notify_data'   => $params['orderType'] == 2 ? json_encode($params) : '',
                'type'          => $type,
            ]
        );
        $order->save();
        return $order;
    }
}
