<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\V1\ChatUser;

class BaseController extends Controller
{

    protected ChatUser $user;

    public function __construct()
    {
        $this->setLang();
        $ip         = request()->ip();
        $this->user = ChatUser::addUser($ip);
    }

    /**
     * 设置语言
     * @return void
     */
    private function setLang(): void
    {
        $defaultLang = env('APP_ENV') == 'prod' ? 'en' : 'zh_CN';
        $lang        = \request()->header('lang', $defaultLang);
        app()->setLocale($lang);
    }


}
