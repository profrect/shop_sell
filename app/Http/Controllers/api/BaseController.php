<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{

    public array $params;
    public function __construct(Request $request)
    {
        $this->params = $request->all();

    }


}
