<?php

namespace App\Models;

use App\Http\Services\SystemLogService;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string $create_time
 * @property string $update_time
 * @property string $delete_time
 * @property-read \App\Models\SystemAdmin|null $admin
 * @method static \Illuminate\Database\Eloquent\Builder|SystemLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemLog query()
 * @mixin \Eloquent
 */
class SystemLog extends BaseModel
{

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'system_log_' . date('Ym');
    }

    public function admin(): HasOne
    {
        return $this->hasOne(SystemAdmin::class, 'id', 'admin_id')->select('id','username');
    }

    public function setMonth($month): static
    {
        SystemLogService::instance()->detectTable();
        $this->table = 'system_log_' . $month;
        return $this;
    }
}
