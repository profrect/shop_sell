<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\common\AdminController;
use App\Http\Services\annotation\NodeAnnotation;
use App\Http\Services\annotation\ControllerAnnotation;

/**
 * @ControllerAnnotation(title="files")
 */
class FilesController extends AdminController
{

    public function initialize()
    {
        parent::initialize();
        $this->model = new \App\Models\Files();
        
    }

}
