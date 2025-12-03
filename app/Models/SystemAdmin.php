<?php

namespace App\Models;

use App\Http\Services\ImChatService;
use App\Models\V1\ChatUser;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property string|null $auth_ids 角色权限ID
 * @property string|null $head_img 头像
 * @property string $username 用户登录名
 * @property string $password 用户登录密码
 * @property string|null $phone 联系手机号
 * @property string|null $remark 备注说明
 * @property int|null $login_num 登录次数
 * @property int|null $sort 排序
 * @property int $status 状态(0:禁用,1:启用,)
 * @property string|null $create_time 创建时间
 * @property string|null $update_time 更新时间
 * @property string|null $delete_time 删除时间
 * @property int $login_type 登录方式
 * @property string $ga_secret 谷歌验证码秘钥
 * @property string|null $im_id imid
 * @method static Builder|SystemAdmin newModelQuery()
 * @method static Builder|SystemAdmin newQuery()
 * @method static Builder|SystemAdmin query()
 * @method static Builder|SystemAdmin whereAuthIds($value)
 * @method static Builder|SystemAdmin whereCreateTime($value)
 * @method static Builder|SystemAdmin whereDeleteTime($value)
 * @method static Builder|SystemAdmin whereGaSecret($value)
 * @method static Builder|SystemAdmin whereHeadImg($value)
 * @method static Builder|SystemAdmin whereId($value)
 * @method static Builder|SystemAdmin whereImId($value)
 * @method static Builder|SystemAdmin whereLoginNum($value)
 * @method static Builder|SystemAdmin whereLoginType($value)
 * @method static Builder|SystemAdmin wherePassword($value)
 * @method static Builder|SystemAdmin wherePhone($value)
 * @method static Builder|SystemAdmin whereRemark($value)
 * @method static Builder|SystemAdmin whereSort($value)
 * @method static Builder|SystemAdmin whereStatus($value)
 * @method static Builder|SystemAdmin whereUpdateTime($value)
 * @method static Builder|SystemAdmin whereUsername($value)
 * @mixin \Eloquent
 */
class SystemAdmin extends BaseModel
{
    public array $notes = [
        'login_type' => [
            1 => '密码登录',
            2 => '密码 + 谷歌验证码登录'
        ],
    ];

    protected static function booted(): void
    {
        static::created(function ($model) {
            if (!$model->im_id) {
                self::registerIm($model);
            }
        });
        static::updated(function ($model) {
            if (!$model->im_id) {
                self::registerIm($model);
            }
        });
    }

    private static function registerIm(self $admin): void
    {
        $username = ChatUser::userName('admin', $admin->username);
        (new ImChatService())->registerUserByName(['username' => $username, 'id' => $admin->id]);
    }

    public function getAuthList(): array
    {
        $list = SystemAuth::where('status', 1)->select(['id', 'title'])->get()->toArray();
        return collect($list)->pluck('title', 'id')->toArray();
    }
}
