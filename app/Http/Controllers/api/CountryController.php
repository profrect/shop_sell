<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\V1\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CountryController extends Controller
{

    /**
     * 获取国家列表
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        $countries = Country::query()->select(['name_en','name_zh'])->get()->toArray();
        return apiSuccess($countries);
    }
}
