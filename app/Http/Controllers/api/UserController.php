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
        $ip       = $request->ip();
        $platform = $request->input('platform', 'web');
        $type     = $request->input('type', 'web');
        $version  = $request->input('version', '1.0.0');
        $user     = ChatUser::query()->where('ip', $ip)
            ->whereTime('update_time', '>=', strtotime('today') - 30 * 24 * 2600)
            ->first();
        if (!$user) {
            $user = ChatUser::query()->create(
                [
                    'ip'       => $ip,
                    'username' => ChatUser::userName(),
                    'device'   => $request->input('device', ''),
                    'create_time' => time(),
                    'update_time' => time(),
                ]
            );
            (new ImChatService())->registerUserByName(['username' => $user->username, 'id' => $user->id]);
            $user = ChatUser::query()->find($user->id);
        }
        $sign = (new ImChatService())->signChat($user->im_id, $platform, $type, $version);
        return apiSuccess($sign);
    }
}
