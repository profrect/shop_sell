<?php

namespace App\Models\V1;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property string $type 图片类型
 * @property string|null $title 标题
 * @property string $url 地址
 * @property string $create_time
 * @property string $update_time
 * @property string $delete_time
 * @method static Builder|Files newModelQuery()
 * @method static Builder|Files newQuery()
 * @method static Builder|Files query()
 * @method static Builder|Files whereCreateTime($value)
 * @method static Builder|Files whereId($value)
 * @method static Builder|Files whereTitle($value)
 * @method static Builder|Files whereType($value)
 * @method static Builder|Files whereUpdateTime($value)
 * @method static Builder|Files whereUrl($value)
 * @mixin \Eloquent
 */
class Files extends BaseModel
{

    protected $appends = ['img_url'];

    public static array $types = [
        'big'    => '顶部大图',
        'small'  => '顶部小图',
        'middle' => '中间图片',
        'risk'   => '风险图片',
        'last'   => '底部图片',
        'wallet' => '钱包图片'
    ];

    // 添加一个获取图片地址的属性
    public function getImgUrlAttribute(): string
    {
        return env('APP_URL').'/'.$this->url;
    }

}
