<?php

namespace App\Models;

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
 * @method static \Illuminate\Database\Eloquent\Builder|MallCate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MallCate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MallCate query()
 * @method static \Illuminate\Database\Eloquent\Builder|MallCate whereCreateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MallCate whereDeleteTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MallCate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MallCate whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MallCate whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MallCate whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MallCate whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MallCate whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MallCate whereUpdateTime($value)
 * @mixin \Eloquent
 */
class MallCate extends BaseModel
{
}
