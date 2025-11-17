<?php

namespace App\Models;

/**
 * @property int $id
 * @property string $title 快捷入口名称
 * @property string|null $icon 图标
 * @property string|null $href 快捷链接
 * @property int|null $sort 排序
 * @property int|null $status 状态(1:禁用,2:启用)
 * @property string|null $remark 备注说明
 * @property string|null $create_time 创建时间
 * @property string|null $update_time 更新时间
 * @property string|null $delete_time 删除时间
 * @method static \Illuminate\Database\Eloquent\Builder|SystemQuick newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemQuick newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemQuick query()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemQuick whereCreateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemQuick whereDeleteTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemQuick whereHref($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemQuick whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemQuick whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemQuick whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemQuick whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemQuick whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemQuick whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemQuick whereUpdateTime($value)
 * @mixin \Eloquent
 */
class SystemQuick extends BaseModel
{
}
