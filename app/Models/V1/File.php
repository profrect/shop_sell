<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $type 图片类型
 * @property string|null $title 标题
 * @property string $url 地址
 * @property int $created_time
 * @property int $updated_time
 * @method static Builder|File newModelQuery()
 * @method static Builder|File newQuery()
 * @method static Builder|File query()
 * @method static Builder|File whereCreatedTime($value)
 * @method static Builder|File whereId($value)
 * @method static Builder|File whereTitle($value)
 * @method static Builder|File whereType($value)
 * @method static Builder|File whereUpdatedTime($value)
 * @method static Builder|File whereUrl($value)
 * @mixin \Eloquent
 */
class File extends Model
{

}
