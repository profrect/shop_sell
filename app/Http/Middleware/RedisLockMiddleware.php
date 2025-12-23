<?php

namespace App\Http\Middleware;

use App\Http\Services\V1\RedisService;
use Closure;
use Illuminate\Http\Request;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class RedisLockMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @param string|null $key 锁key模板
     * @param int $timeout 秒
     * @return Response
     * @throws InvalidArgumentException
     */
    public function handle(Request $request, Closure $next, string $key = null, int $timeout = 10): Response
    {
        $lockKey = $this->buildLockKey($request, $key);
        $lockValue = RedisService::lock($lockKey, null, $timeout);
        if ($lockValue === false) {
            exit("false");
        }

        try {
            $request->attributes->set('_redis_lock_key', $lockKey);
            $request->attributes->set('_redis_lock_value', $lockValue);
            return $next($request);
        } finally {
            RedisService::unlock($lockKey, $lockValue);
        }
    }

    protected function buildLockKey(Request $request, ?string $key): string
    {
        if ($key) {
            return preg_replace_callback('/\{(\w+)\}/', function ($matches) use ($request) {
                return $request->input($matches[1], '');
            }, $key);
        }
        return 'lock:' . md5(
                $request->method() . '|' . $request->path() . '|' . json_encode($request->all())
            );
    }
}
