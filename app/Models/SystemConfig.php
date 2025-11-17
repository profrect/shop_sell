<?php

namespace App\Models;

/**
 * @property int $id
 * @property string $name 变量名
 * @property string $group 分组
 * @property string|null $value 变量值
 * @property string|null $remark 备注信息
 * @property int|null $sort
 * @property string|null $create_time 创建时间
 * @property string|null $update_time 更新时间
 * @property string $delete_time
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConfig whereCreateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConfig whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConfig whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConfig whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConfig whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConfig whereUpdateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConfig whereValue($value)
 * @mixin \Eloquent
 */
class SystemConfig extends BaseModel
{

}
