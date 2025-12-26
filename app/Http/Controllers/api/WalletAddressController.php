<?php

namespace App\Http\Controllers\api;


use App\Exceptions\ApiException;
use App\Http\Services\EtPayService;
use App\Http\Services\V1\SfPayService;
use App\Models\V1\OrderModel;
use Illuminate\Http\JsonResponse;
use Psr\SimpleCache\InvalidArgumentException;

class WalletAddressController extends BaseController
{

    protected array $payType = ['alipay', 'wechat', 'usdt'];

    public function __construct(protected EtPayService $etPayService, protected SfPayService $sfPayService)
    {
        parent::__construct();
    }

    /**
     * 获取所有钱包类型
     * @return JsonResponse
     */
    public function option(): JsonResponse
    {
        $this->payType = array_map(function ($item) {
            return [
                'value' => $item,
                'label' => match ($item) {
                    'alipay' => '支付宝',
                    'wechat' => '微信',
                    'usdt' => 'USDT',
                },
            ];
        }, $this->payType);
        return apiSuccess($this->payType);
    }

    /**
     * 获取所有钱包地址
     * @return JsonResponse
     * @throws ApiException
     */
    public function pay(): JsonResponse
    {
        $type = request()->input('type');
        if (!in_array($type, $this->payType)) {
            return apiError('参数错误');
        }
        $orderId = request()->input('order_id');
        if (!$orderId) {
            return apiError('参数错误');
        }
        $order = OrderModel::where('id', $orderId)->first();
        if (!$order) {
            return apiError('订单不存在');
        }
        if ($order->user_id != $this->user->id) {
            return apiError('订单不存在');
        }
        if ($order->status != OrderModel::STATUS_PAYING) {
            return apiError('订单状态错误');
        }
        $code = '1001';
        $res  = match ($type) {
            'alipay', 'wechat' => $this->sfPayService->pay($order, $code, $type),
            'usdt' => $this->sfPayService->digitalPay($order, $code),
        };
        return apiSuccess($res);
    }

}
