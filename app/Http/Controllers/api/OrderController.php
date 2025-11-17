<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\V1\OrdersRequest;
use App\Http\Services\V1\OrdersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends BaseController
{

    //构造函数
    public function __construct(public Request $request, protected OrdersService $orderService)
    {
        parent::__construct();
    }

    /**
     * @param OrdersRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function create(OrdersRequest $request): JsonResponse
    {
        $request->scene('create')->validated();
        $order = $this->orderService->create();
        return apiSuccess(['order_id' => $order->id]);
    }

}
