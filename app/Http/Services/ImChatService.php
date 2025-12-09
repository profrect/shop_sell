<?php

namespace App\Http\Services;

use App\Models\SystemAdmin;
use App\Models\V1\ChatUser;
use App\Models\V1\UserChatGroup;
use Exception;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use phpseclib3\Crypt\RSA;

class ImChatService
{

    // 构造函数
    private string $appid;
    private string $url;

    public function __construct()
    {
        $this->appid = Env::get('IM_APPID');
        $this->url   = Env::get('IM_SERVER');
    }

    public function registerUserByName($data, string $type = 'user'): bool
    {
        $params = [
            'appId'    => (string)$this->appid,
            'username' => (string)$data['username'],
            'nickname' => (string)$data['username'],
        ];
        $res    = Http::asJson()->post($this->url . '/api/open/create-user-by-username', $params)->json();
        if (!empty($res['uid'])) {
            if ($type == 'user') {
                (new ChatUser())->where('id', $data['id'])->update(['im_id' => $res['uid']]);
            } else {
                (new SystemAdmin())->where('id', $data['id'])->update(['im_id' => $res['uid']]);
            }
        }
        return true;
    }

    /**
     * 聊天签名
     * @param $chatId
     * @param $platform
     * @param $type
     * @param $version
     * @return array
     * @throws Exception
     */
    public function signChat($chatId, $platform, $type, $version): array
    {
        $params           = [
            'appId'    => $this->appid,
            'chatId'   => $chatId,
            'platform' => $platform,
            'type'     => $type,
            'version'  => $version,
            'time'     => (int)(microtime(true) * 1000),
        ];
        $dataString       = implode('_', array_values($params));
        $publicKeyPath    = storage_path('Im_Public_Key.pem');
        $publicKeyContent = file_get_contents($publicKeyPath);
        $publicKey        = RSA::loadPublicKey($publicKeyContent)->withPadding(RSA::ENCRYPTION_PKCS1);
        $encrypted        = $publicKey->encrypt($dataString);
        if (!$encrypted) {
            throw new Exception('RSA加密失败');
        }
        return [
            'chatId' => $chatId,
            'sign'   => base64_encode($encrypted),
            'appId'  => $this->appid,
            'time'   => $params['time'],
        ];
    }

    /**
     * 删除群组
     * @param $groupId
     * @return bool
     */
    public function delGroupChat($groupId): bool
    {
        $params = [
            'appId'   => (string)$this->appid,
            'groupId' => (string)$groupId,
        ];

        try {
            Http::asJson()->post($this->url . '/api/open/dismiss-group', $params)->json();
            // 删除群组
            return true;
        } catch (\Exception $e) {
            Log::error("msg:" . $e->getMessage() . ';file:' . $e->getFile() . ';line:' . $e->getLine() . ';trace:' . $e->getTraceAsString() . ';code:' . $e->getCode() . ';');
            return false;
        }
    }

    /**
     * 创建群组
     * @param ChatUser $user
     * @return mixed
     */
    public function createdGroupChat(ChatUser $user): mixed
    {
        // 先删除30内未跟新的组群
        $delGroups = UserChatGroup::where('create_time', '<', now()->subDays(30))->get();
        foreach ($delGroups as $delGroup) {
            $this->delGroupChat($delGroup->id);
            $delGroup->delete();
        }
        //判断组群是否存在
        $group = UserChatGroup::where('user_id', $user->id)->first();
        if ($group) {
            $group->update_time = time();
            $group->save();
            return $group->group_id;
        } else {
            $adminImIds = SystemAdmin::whereNotNull('im_id')
                ->where('status', '1')
                ->select('im_id', 'id')->get();
            if ($adminImIds->isEmpty()) {
                return "";
            }

            $members   = $adminImIds->map(fn($id) => (string)$id)->toArray();
            $adminIds  = $adminImIds->pluck('id')->toArray();
            $members[] = $user->im_id;

            $name   = '咨询客服群';
            $params = [
                'appId'        => (string)$this->appid,
                'name'         => $name,
                'ownerId'      => (string)$adminImIds->first(),
                'memberIdList' => $members,
                'avatar'       => '',
            ];

            try {
                $res = Http::asJson()
                    ->post("{$this->url}/api/open/create-group", $params)
                    ->body();
                if ($res) {
                    $group              = new UserChatGroup();
                    $group->user_id     = $user->id;
                    $group->group_id    = $res;
                    $group->name        = $name;
                    $group->admins      = implode(',', $adminIds);
                    $group->create_time = time();
                    $group->update_time = time();
                    $group->save();
                }
                return $res;
            } catch (\Exception $e) {
                Log::error("创建群组异常：{$e->getMessage()}");
                return "";
            }
        }
    }


}
