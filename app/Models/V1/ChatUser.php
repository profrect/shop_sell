<?php

namespace App\Models\V1;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property string $username 用户名
 * @property string|null $ip IP
 * @property string|null $device 设备唯一码
 * @property string $im_id imID
 * @property string $create_time
 * @property string $update_time
 * @property string $delete_time
 * @method static Builder|ChatUser newModelQuery()
 * @method static Builder|ChatUser newQuery()
 * @method static Builder|ChatUser query()
 * @method static Builder|ChatUser whereCreateTime($value)
 * @method static Builder|ChatUser whereDevice($value)
 * @method static Builder|ChatUser whereId($value)
 * @method static Builder|ChatUser whereImId($value)
 * @method static Builder|ChatUser whereIp($value)
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

}
