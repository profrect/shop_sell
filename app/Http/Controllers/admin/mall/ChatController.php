<?php

namespace App\Http\Controllers\admin\mall;

use App\Http\Controllers\common\AdminController;
use App\Http\Services\ImChatService;
use App\Models\SystemAdmin;
use App\Models\V1\Icon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

/**
 * @ControllerAnnotation(title="icon")
 */
class ChatController extends AdminController
{

    public function initialize(): void
    {
        parent::initialize();
    }

    public function index(): View|JsonResponse
    {
        return $this->fetch();
    }


    public function getSign(): JsonResponse
    {
        $platform = request()->input('platform', 'web');
        $type     = request()->input('type', 'web');
        $version  = request()->input('version', '1.0.0');
        $id       = session('admin.id');
        $model    = new SystemAdmin();
        $row      = $model->find($id);
        $data     = (new ImChatService())->signChat($row->im_id, $platform, $type, $version);
        return json(['data' => $data]);
    }

}
