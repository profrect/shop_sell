<?php

namespace App\Http\Controllers\admin\mall;

use App\Http\Controllers\common\AdminController;
use App\Http\Services\TriggerService;
use App\Models\SystemConfig;
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
        $this->model  = new SystemConfig();
    }

    public function index(): View
    {
        return $this->fetch();
    }

    public function save(): JsonResponse
    {
        if (!request()->ajax()) return $this->error();
        $post         = request()->post();
        $notAddFields = ['_token', 'file', 'group'];
        try {
            $group = $post['group'] ?? '';
            if (empty($group)) return $this->error('保存失败');
            foreach ($post as $key => $val) {
                if (in_array($key, $notAddFields)) continue;
                if ($this->model->where('name', $key)->count()) {
                    $this->model->where('name', $key)->update(['value' => $val,]);
                } else {
                    $this->model->insert(
                        [
                            'name'  => $key,
                            'value' => $val,
                            'group' => $group,
                        ]);
                }
            }
            TriggerService::updateSysconfig();
        } catch (\Exception $e) {
            return $this->error('保存失败:' . $e->getMessage());
        }
        return $this->success('保存成功');
    }

}
