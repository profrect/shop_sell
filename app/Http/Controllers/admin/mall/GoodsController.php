<?php

namespace App\Http\Controllers\admin\mall;

use App\Http\Controllers\common\AdminController;
use App\Http\Services\annotation\NodeAnnotation;
use App\Http\Services\annotation\ControllerAnnotation;
use App\Models\Format;
use App\Models\GoodsFormat;
use App\Models\MallGoods;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

/**
 * @ControllerAnnotation(title="商城商品管理")
 */
class GoodsController extends AdminController
{

    public function initialize()
    {
        parent::initialize();
        $this->model = new MallGoods();
    }

    /**
     * @NodeAnnotation(title="列表")
     */
    public function index(): View|JsonResponse
    {
        if (!request()->ajax()) return $this->fetch();
        list($page, $limit, $where) = $this->buildTableParams();
        $count = $this->model->where($where)->count();
        $list  = $this->model->where($where)->with(['cate'])->orderByDesc($this->order)->paginate($limit)->items();
        $data  = [
            'code'  => 0,
            'msg'   => '',
            'count' => $count,
            'data'  => $list,
        ];
        return json($data);
    }

    /**
     * @NodeAnnotation(title="入库")
     */
    public function stock(): View|JsonResponse
    {
        $id  = request()->input('id');
        $row = $this->model->find($id);
        if (empty($row)) return $this->error('数据不存在');
        if (request()->ajax()) {
            $post = request()->post();
            try {
                $params['total_stock'] = $row->total_stock + $post['stock'];
                $params['stock']       = $row->stock + $post['stock'];
                $save                  = updateFields($this->model, $row, $params);
            } catch (\Exception $e) {
                return $this->error('保存失败');
            }
            return $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $this->assign(compact('row'));
        return $this->fetch();
    }

    public function format(): View|JsonResponse
    {
        $id  = request()->input('id');
        $row = $this->model->find($id);
        if (empty($row)) return $this->error('数据不存在');
        $format = Format::query()->orderByDesc('sort')->get()->toArray();
        if (request()->ajax()) {
            $post    = request()->post();
            $nowTime = time();
            try {
                $insert       = [];
                $goods_format = GoodsFormat::query()->where('goods_id', $id)->get()->keyBy('format_id');
                foreach ($format as $val) {
                    $data = [
                        'goods_id'    => $id,
                        'format_id'   => $val['id'],
                        'content'     => $post['format'][$val['id']]['content'] ?? '',
                        'sort'        => $post['format'][$val['id']]['sort'] ?? 0,
                        'create_time' => $nowTime,
                        'update_time' => $nowTime,
                        'id'          => 0,
                    ];
                    if (isset($goods_format[$val['id']])) {
                        $data['id'] = $goods_format[$val['id']]->id;
                    }
                    $insert[] = $data;
                }
                GoodsFormat::query()->upsert($insert, ['id']);
            } catch (\Exception $e) {
                return $this->error('保存失败');
            }
            return $this->success('保存成功');
        }
        $row->getFormat;

        $this->assign(compact('row', 'format'));
        return $this->fetch();
    }


}
