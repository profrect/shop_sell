<?php

namespace App\Http\Controllers\api;

use App\Exceptions\ApiException;
use App\Http\Requests\V1\OrdersRequest;
use App\Http\Services\EtPayService;
use App\Http\Services\V1\OrdersService;
use App\Models\V1\ChatUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Psr\SimpleCache\InvalidArgumentException;
use Throwable;

class OrderController extends BaseController
{

    //构造函数
    public function __construct(public Request $request, protected OrdersService $orderService)
    {
        parent::__construct();
    }

    /**
     * 确认订单
     * @param OrdersRequest $request
     * @return JsonResponse
     * @throws ApiException
     */
    public function sure(OrdersRequest $request): JsonResponse
    {
        $request->scene('create')->validated();
        $data = $this->orderService->sure();
        return apiSuccess($data);
    }

    /**
     * 创建订单
     * @param OrdersRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function create(OrdersRequest $request): JsonResponse
    {
        $request->scene('create')->validated();
        $data = $this->orderService->create($this->user, $request->input());
        return apiSuccess($data);
    }

    /**
     * 订单详情
     * @param $order_id
     * @return JsonResponse
     * @throws ApiException
     */
    public function detail($order_id): JsonResponse
    {
        $order = $this->orderService->detail($order_id);
        return apiSuccess($order);
    }

    /**
     * 支付回调
     * @return void
     * @throws Throwable
     * @throws InvalidArgumentException
     */
    public function payNotify(): void
    {
        try {
            $params = request()->input();
            Log::info("支付回调参数:" . json_encode($params));
            $res    = (new EtPayService())->notify($params);
            if ($res) die("success");
            die("fail");
        } catch (\Exception $e) {
            Log::error("支付回调错误:" . $e->getMessage() . ';file:' . $e->getFile() . ';line:' . $e->getLine() . ';trace:' . $e->getTraceAsString() . ';code:' . $e->getCode());
            die("fail");
        }
    }

}
