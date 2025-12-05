<?php

namespace App\Models;

use App\Http\Services\SystemLogService;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id ID
 * @property int|null $admin_id 管理员ID
 * @property string $url 操作页面
 * @property string $method 请求方法
 * @property string|null $title 日志标题
 * @property string $content 请求数据
 * @property string|null $response 回调数据
 * @property string $ip IP
 * @property string|null $useragent User-Agent
 * @property string|null $create_time 操作时间
 * @property string $update_time
 * @property string $delete_time
 * @property-read \App\Models\SystemAdmin|null $admin
 * @method static \Illuminate\Database\Eloquent\Builder|SystemLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemLog whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemLog whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemLog whereCreateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemLog whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemLog whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemLog whereResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemLog whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemLog whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemLog whereUseragent($value)
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
