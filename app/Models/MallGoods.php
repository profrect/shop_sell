<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
 * @property-read MallCate|null $cate
 * @method static Builder|MallGoods newModelQuery()
 * @method static Builder|MallGoods newQuery()
 * @method static Builder|MallGoods query()
 * @method static Builder|MallGoods whereCateId($value)
 * @method static Builder|MallGoods whereCreateTime($value)
 * @method static Builder|MallGoods whereDeleteTime($value)
 * @method static Builder|MallGoods whereDescribe($value)
 * @method static Builder|MallGoods whereDiscountPrice($value)
 * @method static Builder|MallGoods whereId($value)
 * @method static Builder|MallGoods whereImages($value)
 * @method static Builder|MallGoods whereLogo($value)
 * @method static Builder|MallGoods whereMarketPrice($value)
 * @method static Builder|MallGoods whereRemark($value)
 * @method static Builder|MallGoods whereSales($value)
 * @method static Builder|MallGoods whereSort($value)
 * @method static Builder|MallGoods whereStatus($value)
 * @method static Builder|MallGoods whereStock($value)
 * @method static Builder|MallGoods whereTitle($value)
 * @method static Builder|MallGoods whereTotalStock($value)
 * @method static Builder|MallGoods whereUpdateTime($value)
 * @method static Builder|MallGoods whereVirtualSales($value)
 * @mixin \Eloquent
 */
class MallGoods extends BaseModel
{

    const int STATUS_DISABLE = 1;// 禁用
    const int STATUS_ENABLE  = 2;// 启用

    public function cate(): HasOne
    {
        return $this->hasOne(MallCate::class, 'id', 'cate_id')->select('id', 'title');
    }

}
