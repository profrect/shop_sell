<?php

namespace App\Models\V1;

use App\Exceptions\ApiException;
use App\Models\BaseModel;
use App\Models\MallGoods;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;


/**
 * @property int $id
 * @property int $order_id 订单id
 * @property string $email 邮箱
 * @property string|null $country 国家
 * @property string|null $city 城市
 * @property string|null $first_name 名字
 * @property string|null $last_name 姓
 * @property string|null $address 地址
 * @property string|null $room 房号
 * @property string|null $zip_code 邮政编码
 * @property string|null $phone 手机号码
 * @property int $agree_email 是否同意：向我发送加密货币新闻、特别优惠和奖励机遇
 * @property int $agree_info 是否同意：保存此信息以供下次使用
 * @property string $create_time 创建时间
 * @property string $update_time 编辑时间
 * @property string $delete_time
 * @method static Builder|OrderAddress newModelQuery()
 * @method static Builder|OrderAddress newQuery()
 * @method static Builder|OrderAddress query()
 * @method static Builder|OrderAddress whereAddress($value)
 * @method static Builder|OrderAddress whereAgreeEmail($value)
 * @method static Builder|OrderAddress whereAgreeInfo($value)
 * @method static Builder|OrderAddress whereCity($value)
 * @method static Builder|OrderAddress whereCountry($value)
 * @method static Builder|OrderAddress whereCreateTime($value)
 * @method static Builder|OrderAddress whereEmail($value)
 * @method static Builder|OrderAddress whereFirstName($value)
 * @method static Builder|OrderAddress whereId($value)
 * @method static Builder|OrderAddress whereLastName($value)
 * @method static Builder|OrderAddress whereOrderId($value)
 * @method static Builder|OrderAddress wherePhone($value)
 * @method static Builder|OrderAddress whereRoom($value)
 * @method static Builder|OrderAddress whereUpdateTime($value)
 * @method static Builder|OrderAddress whereZipCode($value)
 * @mixin \Eloquent
 */
class OrderAddress extends BaseModel
{


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'orders_address';
    }



}
