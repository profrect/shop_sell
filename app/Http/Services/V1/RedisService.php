<?php

namespace App\Http\Services\V1;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Psr\SimpleCache\InvalidArgumentException;

class RedisService
{
    protected static function redis()
    {
        return Cache::store('redis')->getRedis();
    }

    /**
     * 获取唯一锁
     *
     * @param string $key
     * @param string|null $value
     * @param int $timeout 秒
     * @return string|false  成功返回 value，失败返回 false
     * @throws InvalidArgumentException
     */
    public static function lock(string $key, ?string $value = null, int $timeout = 5): false|string
    {
        $value  = $value ?: Str::uuid()->toString();
        $result = self::redis()->set($key, $value, 'NX', 'EX', $timeout);
        return $result ? $value : false;
    }

    /**
     * 释放唯一锁（必须是持有者）
     *
     * @param string $key
     * @param string $value
     * @return bool
     */
    public static function unlock(string $key, string $value): bool
    {
        $lua    = <<<LUA
if redis.call("get", KEYS[1]) == ARGV[1] then
    return redis.call("del", KEYS[1])
else
    return 0
end
LUA;
        $result = self::redis()->eval($lua, 1, $key, $value);
        return $result === 1;
    }
}
