<?php

namespace App\Models;

/**
 * @property int $id ID
 * @property string $upload_type 存储位置
 * @property string|null $original_name 文件原名
 * @property string $url 物理路径
 * @property string $image_width 宽度
 * @property string $image_height 高度
 * @property string $image_type 图片类型
 * @property int $image_frames 图片帧数
 * @property string $mime_type mime类型
 * @property int $file_size 文件大小
 * @property string|null $file_ext
 * @property string $sha1 文件 sha1编码
 * @property string|null $create_time 创建日期
 * @property string|null $update_time 更新时间
 * @property int|null $upload_time 上传时间
 * @property string $delete_time
 * @method static \Illuminate\Database\Eloquent\Builder|SystemUploadfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemUploadfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemUploadfile query()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemUploadfile whereCreateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemUploadfile whereFileExt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemUploadfile whereFileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemUploadfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemUploadfile whereImageFrames($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemUploadfile whereImageHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemUploadfile whereImageType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemUploadfile whereImageWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemUploadfile whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemUploadfile whereOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemUploadfile whereSha1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemUploadfile whereUpdateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemUploadfile whereUploadTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemUploadfile whereUploadType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemUploadfile whereUrl($value)
 * @mixin \Eloquent
 */
class SystemUploadfile extends BaseModel
{
}
