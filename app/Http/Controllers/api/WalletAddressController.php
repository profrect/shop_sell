<?php

namespace App\Http\Controllers\api;


use App\Http\Services\EtPayService;
use Illuminate\Http\JsonResponse;
use Psr\SimpleCache\InvalidArgumentException;

class WalletAddressController extends BaseController
{

    public function __construct(protected EtPayService $etPayService)
    {
        parent::__construct();
    }

    /**
     * 获取所有钱包地址
     * @return JsonResponse
     * @throws InvalidArgumentException
     */
    public function index(): JsonResponse
    {
        $list = $this->etPayService->createdUser($this->user);
        return apiSuccess(['address' => $list, 'time' => time()]);
    }

}
