<?php

namespace App\Repositories\V1;

use App\Models\MallGoods;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GoodsRepository extends BaseRepository
{

    /**
     * 商品详情
     * @param int $id
     * @return Model|Builder|MallGoods|null
     */
    public function detail(int $id): Model|Builder|MallGoods|null
    {
        return MallGoods::query()->where('id', $id)->first();
    }

    /**减少库存
     * @param int $id
     * @param int $num
     * @return bool
     */
    public function reduceStock(int $id, int $num): bool
    {
        return MallGoods::query()->where('id', $id)->decrement('stock', $num);
    }

    /**增加库存
     * @param int $id
     * @param int $num
     * @return bool
     */
    public function addStock(int $id, int $num): bool
    {
        return MallGoods::query()->where('id', $id)->increment('stock', $num);
    }


}
