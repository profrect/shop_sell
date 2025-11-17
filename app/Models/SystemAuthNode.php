<?php

namespace App\Models;

/**
 * @property int $id
 * @property int|null $auth_id 角色ID
 * @property int|null $node_id 节点ID
 * @property string $create_time
 * @property string $update_time
 * @property string $delete_time
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAuthNode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAuthNode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAuthNode query()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAuthNode whereAuthId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAuthNode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAuthNode whereNodeId($value)
 * @mixin \Eloquent
 */
class SystemAuthNode extends BaseModel
{

}
