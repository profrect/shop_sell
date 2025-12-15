<?php

namespace App\Models\V1;

use App\Http\Services\ImChatService;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

/**
 * @property int $id
 * @property string $username 用户名
 * @property string|null $ip IP
 * @property string|null $device 设备唯一码
 * @property string $im_id imID
 * @property string $create_time
 * @property string $update_time
 * @property string|null $pay_id 支付id
 * @property string|null $pay_password 支付密码
 * @property string $delete_time
 * @method static Builder|ChatUser newModelQuery()
 * @method static Builder|ChatUser newQuery()
 * @method static Builder|ChatUser query()
 * @method static Builder|ChatUser whereCreateTime($value)
 * @method static Builder|ChatUser whereDevice($value)
 * @method static Builder|ChatUser whereId($value)
 * @method static Builder|ChatUser whereImId($value)
 * @method static Builder|ChatUser whereIp($value)
 * @method static Builder|ChatUser wherePayId($value)
 * @method static Builder|ChatUser wherePayPassword($value)
 * @method static Builder|ChatUser whereUpdateTime($value)
 * @method static Builder|ChatUser whereUsername($value)
 * @mixin \Eloquent
 */
class ChatUser extends BaseModel
{
    protected $guarded = [];

    public static function userName($type = 'user', $name = ''): string
    {
        do {
            $username = 'simple_shop';
            if ($type == 'user') {
                $username .= '_u_' . substr(md5(microtime()), rand(0, 26), 5);
            } else {
                $username .= '_a_' . $name;
            }
        } while (self::where('username', $username)->exists());
        return $username;
    }

    /**
     * 添加用户
     * @param $ip
     * @param string $device
     * @return Model|Builder|ChatUser
     */
    public static function addUser($ip, $device = ''): Model|Builder|ChatUser
    {
        $cacheKey    = "user_by_ip:{$ip}";
        $lockKey     = "add_user_lock:{$ip}";
        $cacheExpire = 60 * 60;          // 秒，缓存有效期

        $cachedUser = Redis::get($cacheKey);
        if ($cachedUser) {
            return unserialize($cachedUser);
        }

        $lock = Redis::set($lockKey, 1, 'EX', $cacheExpire, 'NX');

        // 查询数据库中最近的用户
        $timeLimit = 7 * 24 * 3600; // 7 天
        $user      = self::where('ip', $ip)
            ->where('create_time', '>', time() - $timeLimit)
            ->first();

        if (!$user && $lock) {
            $username           = self::userName();
            $user               = new self();
            $user->ip           = $ip;
            $user->device       = $device;
            $user->username     = $username;
            $user->pay_password = md5('simple_shop' . $username);
            $user->create_time  = time();
        }
        if ($user) {
            $user->update_time = time();
            $user->save();
            if (!$user->im_id) {
                (new ImChatService())->registerUserByName([
                    'username' => $user->username,
                    'id'       => $user->id,
                ]);
                $user = self::find($user->id);
            }
            Redis::setex($cacheKey, $cacheExpire, serialize($user));
        }
        return $user;
    }


}
