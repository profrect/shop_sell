<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Services\ImChatService;
use App\Models\V1\ChatUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    /**
     * 获取用户签到信息
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function getSign(Request $request): JsonResponse
    {
        $platform      = $request->input('platform', 'web');
        $type          = $request->input('type', 'web');
        $version       = $request->input('version', '1.0.0');
        $sign          = (new ImChatService())->signChat($this->user->im_id, $platform, $type, $version);
        $group         = (new ImChatService())->createdGroupChat($this->user);
        $sign['group'] = $group;
        return apiSuccess($sign);
    }
}
