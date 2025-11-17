<?php

namespace App\Models;

/**
 * @property int $id
 * @property string $title 权限名称
 * @property int|null $sort 排序
 * @property int|null $status 状态(1:禁用,2:启用)
 * @property string|null $remark 备注说明
 * @property string|null $create_time 创建时间
 * @property string|null $update_time 更新时间
 * @property string|null $delete_time 删除时间
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAuth newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAuth newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAuth query()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAuth whereCreateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAuth whereDeleteTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAuth whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAuth whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAuth whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAuth whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAuth whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAuth whereUpdateTime($value)
 * @mixin \Eloquent
 */
class SystemAuth extends BaseModel
{
    /**
     * @param $authId
     * @return array
     */
    public function getAuthorizeNodeListByAdminId($authId): array
    {
        $checkNodeList = SystemAuthNode::where('auth_id', $authId)->pluck('node_id')->toArray();
        $systemNode    = new SystemNode();
        $nodeList      = $systemNode->where('is_auth', 1)->select(explode(',', 'id,node,title,type,is_auth'))->get()->toArray();
        $newNodeList   = [];
        foreach ($nodeList as $vo) {
            if ($vo['type'] == 1) {
                $vo            = array_merge($vo, ['field' => 'node', 'spread' => true]);
                $vo['checked'] = false;
                $vo['title']   = "{$vo['title']}【{$vo['node']}】";
                $children      = [];
                foreach ($nodeList as $v) {
                    if ($v['type'] == 2 && str_contains($v['node'], $vo['node'] . '/')) {

                        $v            = array_merge($v, ['field' => 'node', 'spread' => true]);
                        $v['checked'] = in_array($v['id'], $checkNodeList);
                        $v['title']   = "{$v['title']}【{$v['node']}】";
                        $children[]   = $v;
                    }
                }
                !empty($children) && $vo['children'] = $children;
                $newNodeList[] = $vo;
            }
        }
        return $newNodeList;
    }
}
