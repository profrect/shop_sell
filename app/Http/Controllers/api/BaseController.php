<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{


    public function __construct()
    {
        $this->setLang();
    }

    /**
     * 设置语言
     * @return void
     */
    private function setLang(): void
    {
        $defaultLang = env('APP_ENV') == 'prod' ? 'en' : 'zh_CN';
        $lang = \request()->header('lang',$defaultLang);
        app()->setLocale($lang);
    }


}
