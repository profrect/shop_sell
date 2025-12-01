<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\V1\Icon;
use Illuminate\Http\JsonResponse;

class IconController extends Controller
{

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $list = Icon::query()->orderByDesc('sort')->get()?->toArray();
        return apiSuccess($list);
    }

}
