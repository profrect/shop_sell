<?php

namespace App\Http\Controllers\api;

use App\Repositories\V1\GoodsRepository;
use Illuminate\Http\Request;

class GoodsController extends BaseController
{

    //构造函数
    public function __construct(public Request $request, protected GoodsRepository $goodsRepository)
    {
        parent::__construct();
    }


}
