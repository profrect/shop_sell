<?php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    public     $code;
    public     $message;
    public int $status;

    /**
     * ApiException constructor.
     * @param string $message
     * @param int $code
     * @param int $status
     */
    public function __construct(string $message, int $code = -1, int $status = 400)
    {
        parent::__construct($message, $code);
        $this->message = $message;
        $this->code    = $code;      // 业务码
        $this->status  = $status;    // HTTP 状态码
    }
}
