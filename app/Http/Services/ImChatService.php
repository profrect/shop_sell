<?php

namespace App\Http\Services;

use App\Models\SystemAdmin;
use App\Models\V1\ChatUser;
use Exception;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Http;
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
        $res    = Http::asJson()->post($this->url . '/api/open/create-user-by-username', $params);
        if (!empty($res['uid'])) {
            if ($type == 'user') {
                (new ChatUser())->update(['chat_id' => $res['uid']], ['id' => $data['id']]);
            } else {
                (new SystemAdmin())->update(['chat_id' => $res['uid']], ['id' => $data['id']]);
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
        $publicKey        = RSA::loadPublicKey($publicKeyContent);

        // 加密
        $encrypted = $publicKey->encrypt($dataString);
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


}
