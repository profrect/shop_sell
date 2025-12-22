<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;

class GoodsFormat extends BaseModel
{

    public function format(): HasOne
    {
        return $this->hasOne(Format::class, 'id', 'format_id')->select(['id','title','sort']);
    }



}
