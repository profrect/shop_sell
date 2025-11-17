<?php

namespace App\Models;

/**
 * @property int $id
 * @property string|null $node 节点代码
 * @property string|null $title 节点标题
 * @property int|null $type 节点类型（1：控制器，2：节点）
 * @property int|null $is_auth 是否启动RBAC权限控制
 * @property string|null $create_time 创建时间
 * @property string|null $update_time 更新时间
 * @property string $delete_time
 * @method static \Illuminate\Database\Eloquent\Builder|SystemNode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemNode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemNode query()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemNode whereCreateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemNode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemNode whereIsAuth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemNode whereNode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemNode whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemNode whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemNode whereUpdateTime($value)
 * @mixin \Eloquent
 */
class SystemNode extends BaseModel
{
    public function getNodeTreeList(): array
    {
        $list = $this->get()->toArray();
        return $this->buildNodeTree($list);
    }

    protected function buildNodeTree($list): array
    {
        $newList      = [];
        $repeatString = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        foreach ($list as $vo) {
            if ($vo['type'] == 1) {
                $newList[] = $vo;
                foreach ($list as $v) {
                    if ($v['type'] == 2 && str_contains($v['node'], $vo['node'] . '/')) {
                        $v['node'] = "{$repeatString}├{$repeatString}" . $v['node'];
                        $newList[] = $v;
                    }
                }
            }
        }
        return $newList;
    }
}
