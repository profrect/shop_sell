<?php

namespace App\Http\Controllers\admin\goods;

use App\Http\Controllers\common\AdminController;
use App\Models\Format;

/**
 * @ControllerAnnotation(title="goods_format")
 */
class FormatController extends AdminController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->model = new Format();

    }

}
