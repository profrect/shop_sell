<?php

namespace App\Http\Controllers\api;

use App\Models\BaseModel;
use App\Models\MallCate;
use App\Models\MallGoods;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GoodsController extends BaseController
{

    //构造函数
    public function __construct(public Request $request)
    {
        parent::__construct();
    }

    /**
     * 商品列表
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        $cate = MallCate::query()->where('status', BaseModel::STATUS_ENABLE)
            ->select(['id', 'title', 'remark'])
            ->orderByDesc('sort')
            ->get();
        $cate->map(function ($item) {
            $item['goods'] = MallGoods::query()->where('cate_id', $item['id'])
                ->where('status', BaseModel::STATUS_ENABLE)
                ->select(['id', 'title', 'logo', 'describe', 'discount_price', 'star', 'remark'])
                ->orderByDesc('sort')
                ->get();
        });
        return apiSuccess($cate?->toArray());
    }

    /**
     * 商品详情
     * @param $id
     * @return JsonResponse
     */
    public function detail($id): JsonResponse
    {
        $detail = MallGoods::query()->with(['getFormat' => function ($query) {
            $query->with(['format']);
            return $query;
        }])->where('id', $id)->first()?->toArray();
        $detail['discount'] = bcdiv($detail['discount_price'], $detail['market_price'], 2);
        return apiSuccess($detail);
    }


}
