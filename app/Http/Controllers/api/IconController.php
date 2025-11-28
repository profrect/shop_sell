<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Icon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
