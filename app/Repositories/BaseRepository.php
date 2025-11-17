<?php

namespace App\Repositories;

use Illuminate\Http\Request;

class BaseRepository
{
    protected array $params;
    // 构造函数
    public function __construct(Request $request)
    {
        $this->params = $request->all();
        if(!empty($this->params['page'])){
            if (!empty($this->params['limit'])){
                $this->params['limit'] = min($this->params['limit'], 100);
            }else{
                $this->params['limit'] = 10;
            }
        }
    }

}
