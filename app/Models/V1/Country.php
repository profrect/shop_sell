<?php

namespace App\Models\V1;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;


/**
 * @property string $iso2
 * @property string $iso3
 * @property string $iso_numeric
 * @property string $name_en
 * @property string $name_zh
 * @property string $create_time
 * @property string $update_time
 * @property string $delete_time
 * @method static Builder|Country newModelQuery()
 * @method static Builder|Country newQuery()
 * @method static Builder|Country query()
 * @method static Builder|Country whereIso2($value)
 * @method static Builder|Country whereIso3($value)
 * @method static Builder|Country whereIsoNumeric($value)
 * @method static Builder|Country whereNameEn($value)
 * @method static Builder|Country whereNameZh($value)
 * @mixin \Eloquent
 */
class Country extends BaseModel
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'country';
    }

}
