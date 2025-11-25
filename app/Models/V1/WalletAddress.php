<?php

namespace App\Models\V1;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property string $currency_type 币种类型
 * @property string $protocol_type 协议类型
 * @property string $address 地址
 * @property string $create_time 创建时间
 * @property string $update_time 更新时间
 * @property string $delete_time
 * @method static Builder|WalletAddress newModelQuery()
 * @method static Builder|WalletAddress newQuery()
 * @method static Builder|WalletAddress query()
 * @method static Builder|WalletAddress whereAddress($value)
 * @method static Builder|WalletAddress whereCreateTime($value)
 * @method static Builder|WalletAddress whereCurrencyType($value)
 * @method static Builder|WalletAddress whereId($value)
 * @method static Builder|WalletAddress whereProtocolType($value)
 * @method static Builder|WalletAddress whereUpdateTime($value)
 * @mixin \Eloquent
 */
class WalletAddress extends BaseModel
{



}
