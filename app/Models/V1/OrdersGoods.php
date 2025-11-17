<?php

namespace App\Models\V1;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;


/**
 * @property int $id
 * @property int $order_id 订单id
 * @property int $goods_id 商品id
 * @property int $goods_num 商品数量
 * @property string|null $goods_title 商品名称
 * @property string $goods_price 商品单价
 * @property string $goods_all 商品总价
 * @property int $created_time 创建时间
 * @property int $updated_time 更新时间
 * @property string $create_time
 * @property string $update_time
 * @property string $delete_time
 * @method static Builder|OrdersGoods newModelQuery()
 * @method static Builder|OrdersGoods newQuery()
 * @method static Builder|OrdersGoods query()
 * @method static Builder|OrdersGoods whereCreatedTime($value)
 * @method static Builder|OrdersGoods whereGoodsAll($value)
 * @method static Builder|OrdersGoods whereGoodsId($value)
 * @method static Builder|OrdersGoods whereGoodsNum($value)
 * @method static Builder|OrdersGoods whereGoodsPrice($value)
 * @method static Builder|OrdersGoods whereGoodsTitle($value)
 * @method static Builder|OrdersGoods whereId($value)
 * @method static Builder|OrdersGoods whereOrderId($value)
 * @method static Builder|OrdersGoods whereUpdatedTime($value)
 * @mixin \Eloquent
 */
class OrdersGoods extends BaseModel
{


}
