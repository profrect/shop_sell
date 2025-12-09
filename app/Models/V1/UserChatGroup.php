<?php

namespace App\Models\V1;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property int $user_id
 * @property string $admins 管理员id
 * @property string $group_id 组群id
 * @property string|null $name
 * @property string $create_time
 * @property string $update_time
 * @property string $delete_time
 * @method static Builder|UserChatGroup newModelQuery()
 * @method static Builder|UserChatGroup newQuery()
 * @method static Builder|UserChatGroup query()
 * @method static Builder|UserChatGroup whereAdmins($value)
 * @method static Builder|UserChatGroup whereCreateTime($value)
 * @method static Builder|UserChatGroup whereGroupId($value)
 * @method static Builder|UserChatGroup whereId($value)
 * @method static Builder|UserChatGroup whereName($value)
 * @method static Builder|UserChatGroup whereUpdateTime($value)
 * @method static Builder|UserChatGroup whereUserId($value)
 * @mixin \Eloquent
 */
class UserChatGroup extends BaseModel
{
    protected $table = 'user_chat_group';
    protected $guarded = [];
}
