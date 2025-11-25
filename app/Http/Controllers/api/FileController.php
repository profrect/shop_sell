<?php

namespace App\Http\Controllers\api;

use App\Models\V1\Files;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FileController extends BaseController
{

    //构造函数
    public function __construct(public Request $request)
    {
        parent::__construct();
    }

    /**
     * 列表
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        $list = Files::query()->get();
        return apiSuccess($list);
    }


}
