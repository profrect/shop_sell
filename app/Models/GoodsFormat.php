<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $goods_id 商品id
 * @property int $format_id 规格id
 * @property int $sort 排序
 * @property string|null $content 内容
 * @property string $create_time 创建时间
 * @property string $update_time 更新时间
 * @property string $delete_time
 * @property-read \App\Models\Format|null $format
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsFormat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsFormat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsFormat query()
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsFormat whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsFormat whereCreateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsFormat whereFormatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsFormat whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsFormat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsFormat whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsFormat whereUpdateTime($value)
 * @mixin \Eloquent
 */
class GoodsFormat extends BaseModel
{

    public function format(): HasOne
    {
        return $this->hasOne(Format::class, 'id', 'format_id')->select(['id','title','sort']);
    }



}
