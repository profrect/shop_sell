<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int|null $cate_id 分类ID
 * @property string $title 商品名称
 * @property string|null $logo 商品logo
 * @property string|null $images 商品图片 以 | 做分割符号
 * @property string|null $describe 商品描述
 * @property string|null $market_price 市场价
 * @property string|null $discount_price 折扣价
 * @property int|null $sales 销量
 * @property int|null $virtual_sales 虚拟销量
 * @property int|null $stock 库存
 * @property int|null $total_stock 总库存
 * @property int|null $sort 排序
 * @property int|null $status 状态(1:禁用,2:启用)
 * @property string|null $remark 备注说明
 * @property string|null $create_time 创建时间
 * @property string|null $update_time 更新时间
 * @property string|null $delete_time 删除时间
 * @property-read \App\Models\MallCate|null $cate
 * @method static \Illuminate\Database\Eloquent\Builder|MallGoods newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MallGoods newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MallGoods query()
 * @method static \Illuminate\Database\Eloquent\Builder|MallGoods whereCateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MallGoods whereCreateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MallGoods whereDeleteTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MallGoods whereDescribe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MallGoods whereDiscountPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MallGoods whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MallGoods whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MallGoods whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MallGoods whereMarketPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MallGoods whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MallGoods whereSales($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MallGoods whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MallGoods whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MallGoods whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MallGoods whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MallGoods whereTotalStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MallGoods whereUpdateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MallGoods whereVirtualSales($value)
 * @mixin \Eloquent
 */
class MallGoods extends BaseModel
{

    public function cate(): HasOne
    {
        return $this->hasOne(MallCate::class, 'id', 'cate_id')->select('id', 'title');
    }

}
