<?php

namespace App\Http\Controllers\admin\mall;

use App\Http\Controllers\common\AdminController;
use App\Models\Icon;

/**
 * @ControllerAnnotation(title="icon")
 */
class IconController extends AdminController
{

    public function initialize()
    {
        parent::initialize();
        $this->model = new Icon();

    }

}
