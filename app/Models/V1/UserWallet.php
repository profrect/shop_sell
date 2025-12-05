<?php

namespace App\Models\V1;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property int|null $user_id 平台用户id
 * @property string $currency_type 币种类型
 * @property string $protocol_type 协议类型
 * @property string $address 地址
 * @property float $balance 余额
 * @property int|null $in_times 收款次数
 * @property string|null $create_time
 * @property string|null $update_time
 * @property string $delete_time
 * @method static Builder|UserWallet newModelQuery()
 * @method static Builder|UserWallet newQuery()
 * @method static Builder|UserWallet query()
 * @method static Builder|UserWallet whereAddress($value)
 * @method static Builder|UserWallet whereBalance($value)
 * @method static Builder|UserWallet whereCreateTime($value)
 * @method static Builder|UserWallet whereCurrencyType($value)
 * @method static Builder|UserWallet whereId($value)
 * @method static Builder|UserWallet whereInTimes($value)
 * @method static Builder|UserWallet whereProtocolType($value)
 * @method static Builder|UserWallet whereUpdateTime($value)
 * @method static Builder|UserWallet whereUserId($value)
 * @mixin \Eloquent
 */
class UserWallet extends BaseModel
{
    protected $table = 'user_wallet';
    protected $guarded = [];

    const TYPE_GROUP = ['ERC20', 'TRC20', 'BSC'];
}
