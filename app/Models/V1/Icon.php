<?php

namespace App\Models\V1;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id id
 * @property string|null $icon 图标
 * @property int $sort 排序
 * @property string $create_time 创建时间
 * @property string $update_time 更新时间
 * @property string $delete_time
 * @method static Builder|Icon newModelQuery()
 * @method static Builder|Icon newQuery()
 * @method static Builder|Icon query()
 * @method static Builder|Icon whereCreateTime($value)
 * @method static Builder|Icon whereIcon($value)
 * @method static Builder|Icon whereId($value)
 * @method static Builder|Icon whereSort($value)
 * @method static Builder|Icon whereUpdateTime($value)
 * @mixin \Eloquent
 */
class Icon extends BaseModel
{

}
