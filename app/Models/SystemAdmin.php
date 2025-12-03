<?php

namespace App\Models;

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
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAdmin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAdmin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAdmin query()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAdmin whereAuthIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAdmin whereCreateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAdmin whereDeleteTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAdmin whereGaSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAdmin whereHeadImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAdmin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAdmin whereImId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAdmin whereLoginNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAdmin whereLoginType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAdmin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAdmin wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAdmin whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAdmin whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAdmin whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAdmin whereUpdateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemAdmin whereUsername($value)
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

    public function getAuthList(): array
    {
        $list = SystemAuth::where('status', 1)->select(['id', 'title'])->get()->toArray();
        return collect($list)->pluck('title', 'id')->toArray();
    }
}
