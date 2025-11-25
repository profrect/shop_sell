<?php

namespace App\Http\Controllers\admin\mall;

use App\Http\Controllers\common\AdminController;
use App\Models\V1\Files;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

/**
 * @ControllerAnnotation(title="files")
 */
class FilesController extends AdminController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->model = new Files();
        $this->assign(['types' => Files::$types]);
    }

    public function index(): JsonResponse|View
    {
        if (!request()->ajax()) return $this->fetch();
        if (request()->input('selectFields')) {
            return $this->selectList();
        }
        list($page, $limit, $where) = $this->buildTableParams();
        $count = $this->model->where($where)->count();
        $list  = $this->model->where($where)->orderByDesc($this->order)->paginate($limit)->items();
        foreach ($list as &$item) {
            $item['type_name'] = Files::$types[$item['type']];
        }
        $data  = [
            'code'  => 0,
            'msg'   => '',
            'count' => $count,
            'data'  => $list,
        ];
        return json($data);
    }

}
