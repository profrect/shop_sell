<?php

namespace App\Http\Services\V1;

use App\Exceptions\ApiException;
use App\Models\V1\OrderAddress;
use App\Models\V1\OrderModel;
use App\Models\V1\OrdersPay;
use DB;
use Illuminate\Support\Facades\Http;

class SfPayService
{
    private string $base_url;
    private string $api_key;
    private string $api_id;

    public function __construct()
    {
        $this->base_url = env('SFPAY_URL');
        $this->api_key  = env('SFPAY_APPKEY');
        $this->api_id   = env('SFPAY_APPID');
    }

    /**
     * 法币创建支付
     * @param OrderModel $order
     * @param $code
     * @param string $payType
     * @return array
     * @throws ApiException
     */
    public function pay(OrderModel $order, $code, string $payType = 'alipay'): array
    {
        $action = '/v1/api/pay/create/order';
        $notify_url     = env('APP_URL') . '/api/order/sfPayNotify';
        $params         = [
            'merchant_id'       => $this->api_id,
            'merchant_order_sn' => $order->number,
            'amount'            => bcdiv($order->total_money, 1, 2),
            'currency'          => 'USD', //USD,CNY
            'product_code'      => $code, //alipay,wechat
            'goods_name'        => $order->goods[0]['goods_title'],
            'notify_url'        => $notify_url, //回调地址
            'payer'             => [
                'user_id' => $order->user_id,
                'name'    => $order->orderAddress->first_name . ' ' . $order->orderAddress->last_name,
                'email'   => $order->orderAddress->email,
                'phone'   => $order->orderAddress->phone,
            ],
            'timestamp'         => time()
        ];
        $params['sign'] = $this->sign($params);
        $res            = Http::asForm()->post($this->base_url . $action, $params)->json();
        if ($res['code'] == 200) {
            $data   = $res['data'];
            $payLog = OrdersPay::addLog($order, $payType);
            $payLog->update([
                'status'   => $data['status'],
                'order_sn' => $data['order_sn'],
                'response' => json_encode($res['data'], JSON_UNESCAPED_UNICODE),
            ]);
            return ['payment_url' => $res['data']['payment_url']];
        }
        \Log::info('创建支付失败', [$res]);
        throw new ApiException("创建支付失败");
    }

    /**
     * 法币支付回调
     * @param array $data
     * @return bool
     * @throws \Throwable
     */
    public function notify(array $data): bool
    {
        DB::beginTransaction();

        try {
            if (!$this->verifySign($data)) {
                throw new ApiException('sfPay验签失败');
            }

            $order = OrderModel::where('number', $data['merchant_order_sn'])->first();
            if (!$order) {
                throw new ApiException('订单不存在');
            }

            $affected = OrderModel::where('id', $order->id)
                ->where('status', OrderModel::STATUS_PAYING)
                ->update([
                    'status'      => $data['status'] === 'SUCCESS'
                        ? OrderModel::STATUS_WAIT_SEND
                        : OrderModel::STATUS_CANCEL,
                    'pay_time'    => time(),
                    'update_time' => time(),
                ]);

            if ($affected === 0) {
                DB::commit();
                return true;
            }

            OrdersPay::where('order_id', $order->id)
                ->where('status', OrdersPay::PAY_STATUS_WAIT)
                ->update([
                    'status'      => $data['status'] === 'SUCCESS'
                        ? OrdersPay::PAY_STATUS_SUCCESS
                        : OrdersPay::PAY_STATUS_FAIL,
                    'pay_time'    => time(),
                    'update_time' => time(),
                    'response'    => json_encode($data, JSON_UNESCAPED_UNICODE),
                ]);

            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('sfPay回调异常', [$e->getMessage(), $data]);
            return false;
        }
    }


    /**
     * 数字货币创建支付
     * @param OrderModel $order
     * @param string $payType
     * @return array
     * @throws ApiException
     */
    public function digitalPay(OrderModel $order, string $code): array
    {
        $action = '/v1/api/digital/pay/create/order';
        /* @var $address OrderAddress; */
        $address = $order->address;
        $payType = 'USDT';

        $notify_url     = env('APP_URL') . '/api/order/sfPayDigitalNotify';
        $params         = [
            'merchant_id'       => $this->api_id,
            'merchant_order_sn' => $order->number,
            'amount'            => bcdiv($order->total_money, 1, 6),
            'currency'          => $payType,
            'product_code'      => $code,
            'goods_name'        => $order->goods[0]['goods_title'],
            'notify_url'        => $notify_url,
            'lang'              => 'zh',
            'payer'             => [
                'user_id' => $order->user_id,
                'name'    => $address->first_name . ' ' . $address->last_name,
                'email'   => $address->email,
                'phone'   => $address->phone,
            ],
            'timestamp'         => time()
        ];
        $params['sign'] = $this->sign($params);
        $res            = Http::asForm()->post($this->base_url . $action, $params)->json();
        if ($res['code'] == 200) {
            $data   = $res['data'];
            $payLog = OrdersPay::addLog($order, $payType, OrdersPay::PAY_TYPE_USDT);
            $payLog->update([
                'status'   => $data['status'],
                'order_sn' => $data['order_sn'],
                'response' => json_encode($res['data'], JSON_UNESCAPED_UNICODE),
            ]);
            return [
                'payment_url' => $res['data']['payment_url'],
                'qr_code'     => $res['data']['qr_code'],
                'crypto_info' => $res['data']['crypto_info'],
            ];
        }
        \Log::info('创建支付失败', [$res]);
        throw new ApiException("创建支付失败");
    }

    /**
     * 数字货币支付回调
     * @param array $data
     * @return bool
     * @throws \Throwable
     */
    public function digitalNotify(array $data): bool
    {
        DB::beginTransaction();

        try {
            if (!$this->verifySign($data)) {
                throw new ApiException('sfPay验签失败');
            }

            $order = OrderModel::where('number', $data['merchant_order_sn'])->first();
            if (!$order) {
                throw new ApiException('订单不存在');
            }

            // 待支付
            if ($data['status'] == 0 || $data['status'] == 1) {
                DB::commit();
                return true;
            }

            $affected = OrderModel::where('id', $order->id)
                ->where('status', OrderModel::STATUS_PAYING)
                ->update([
                    'status'      => $data['status'] === '2'
                        ? OrderModel::STATUS_WAIT_SEND
                        : OrderModel::STATUS_CANCEL,
                    'pay_time'    => strtotime($data['paid_at']),
                    'update_time' => time(),
                ]);

            if ($affected === 0) {
                DB::commit();
                return true;
            }

            OrdersPay::where('order_id', $order->id)
                ->where('status', OrdersPay::PAY_STATUS_WAIT)
                ->update([
                    'status'      => $data['status'] === '2'
                        ? OrdersPay::PAY_STATUS_SUCCESS
                        : OrdersPay::PAY_STATUS_FAIL,
                    'pay_time'    => strtotime($data['paid_at']),
                    'update_time' => time(),
                    'response'    => json_encode($data, JSON_UNESCAPED_UNICODE),
                ]);

            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('sfPay回调异常', [$e->getMessage(), $data]);
            return false;
        }
    }


    /**
     * 签名
     * @param array $data
     * @return string
     */
    private function sign(array $data = []): string
    {
        if (isset($data['sign'])) {
            unset($data['sign']);
        }
        ksort($data);
        $string = "";
        foreach ($data as $k => $v) {
            if ($v == '' || is_array($v)) {
                continue;
            }
            $string .= $k . "=" . $v . "&";
        }
        $string .= 'key=' . $this->api_key;
        return strtoupper(md5($string));
    }

    /**
     * 验证签名
     * @param $data
     * @return bool
     */
    private function verifySign($data): bool
    {
        $sign = $data['sign'];
        unset($data['sign']);
        $data = array_filter($data, function ($value) {
            return $value !== '' && $value !== null && !is_array($value);
        });
        ksort($data);
        $string = '';
        foreach ($data as $key => $value) {
            $string .= $key . '=' . $value . '&';
        }
        $string          .= 'key=' . $this->api_key;
        $calculated_sign = strtoupper(md5($string));
        return $calculated_sign === $sign;
    }

}
