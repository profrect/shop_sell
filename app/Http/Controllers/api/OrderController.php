<?php

namespace App\Http\Controllers\api;

use App\Exceptions\ApiException;
use App\Http\Requests\V1\OrdersRequest;
use App\Http\Services\V1\OrdersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

}
