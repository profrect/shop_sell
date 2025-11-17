<?php

namespace App\Models;

use App\Http\Services\SystemLogService;

/**
 * @property int $id
 * @property int $pid 父id
 * @property string $title 名称
 * @property string $icon 菜单图标
 * @property string $href 链接
 * @property string|null $params 链接参数
 * @property string $target 链接打开方式
 * @property int|null $sort 菜单排序
 * @property int $status 状态(0:禁用,1:启用)
 * @property string|null $remark
 * @property string|null $create_time 创建时间
 * @property string|null $update_time 更新时间
 * @property string|null $delete_time 删除时间
 * @method static \Illuminate\Database\Eloquent\Builder|SystemMenu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemMenu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemMenu query()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemMenu whereCreateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemMenu whereDeleteTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemMenu whereHref($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemMenu whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemMenu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemMenu whereParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemMenu wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemMenu whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemMenu whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemMenu whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemMenu whereTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemMenu whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemMenu whereUpdateTime($value)
 * @mixin \Eloquent
 */
class SystemMenu extends BaseModel
{
    public function getPidMenuList(): array
    {
        $list        = $this->select(explode(',', 'id,pid,title'))
            ->where([
                        ['pid', '<>', HOME_PID],
                        ['status', '=', 1],
                    ])->get()->toArray();
        $pidMenuList = $this->buildPidMenu(0, $list);
        return array_merge([['id' => 0, 'pid' => 0, 'title' => '顶级菜单']], $pidMenuList);
    }

    protected function buildPidMenu($pid, $list, $level = 0): array
    {
        $newList = [];
        foreach ($list as $vo) {
            if ($vo['pid'] == $pid) {
                $level++;
                foreach ($newList as $v) {
                    if ($vo['pid'] == $v['pid'] && isset($v['level'])) {
                        $level = $v['level'];
                        break;
                    }
                }
                $vo['level'] = $level;
                if ($level > 1) {
                    $repeatString = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    $markString   = str_repeat("{$repeatString}├{$repeatString}", $level - 1);
                    $vo['title']  = $markString . $vo['title'];
                }
                $newList[] = $vo;
                $childList = $this->buildPidMenu($vo['id'], $list, $level);
                !empty($childList) && $newList = array_merge($newList, $childList);
            }

        }
        return $newList;
    }
}
