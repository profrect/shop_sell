<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property string $title 分类名
 * @property string|null $image 分类图片
 * @property int|null $sort 排序
 * @property int|null $status 状态(1:禁用,2:启用)
 * @property string|null $remark 备注说明
 * @property string|null $create_time 创建时间
 * @property string|null $update_time 更新时间
 * @property string|null $delete_time 删除时间
 * @method static Builder|MallCate newModelQuery()
 * @method static Builder|MallCate newQuery()
 * @method static Builder|MallCate query()
 * @method static Builder|MallCate whereCreateTime($value)
 * @method static Builder|MallCate whereDeleteTime($value)
 * @method static Builder|MallCate whereId($value)
 * @method static Builder|MallCate whereImage($value)
 * @method static Builder|MallCate whereRemark($value)
 * @method static Builder|MallCate whereSort($value)
 * @method static Builder|MallCate whereStatus($value)
 * @method static Builder|MallCate whereTitle($value)
 * @method static Builder|MallCate whereUpdateTime($value)
 * @mixin \Eloquent
 */
class MallCate extends BaseModel
{
}
