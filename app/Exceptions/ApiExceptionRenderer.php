<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class ApiExceptionRenderer
{
    /**
     * 渲染异常
     *
     * @param Request $request
     * @param Throwable $e
     * @return JsonResponse
     */
    public static function render(Request $request, Throwable $e): JsonResponse
    {
        // 其他异常（系统异常）
        \Log::error('错误日志:{msg};file:{file};line:{line};router:{router};params:{params}', [
            'msg'    => $e->getMessage(),
            'file'   => $e->getFile(),
            'line'   => $e->getLine(),
            'router' => $request->route(),
            'params' => $request->all()
        ]);
        return apiError($e->getMessage(), $e->getCode() ?? -1);
    }
}
