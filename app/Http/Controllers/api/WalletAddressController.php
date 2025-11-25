<?php

namespace App\Http\Controllers\api;


use App\Models\V1\WalletAddress;
use Illuminate\Http\JsonResponse;

class WalletAddressController extends BaseController
{

    /**
     * 获取所有钱包地址
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $list = WalletAddress::query()->groupBy('currency_type')->groupBy('protocol_type')
            ->get();
        return apiSuccess($list);
    }

}
