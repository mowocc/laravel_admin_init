<?php
namespace App\Models\Admin\Auth;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Traits\DatabaseBuilder;
use App\Models\Traits\Admin\LogsActivity;
use App\Models\Traits\Admin\HasRoles;
use App\Models\Traits\Admin\UserRoles;
use App\Models\Traits\SuperAdmin;

class User extends Authenticatable // implements MustVerifyEmail
{
    use SoftDeletes, Notifiable, DatabaseBuilder, LogsActivity, HasRoles, UserRoles, SuperAdmin;

    /**
     * 【活动日志】从日志中忽略的属性
     *
     * @var array
     */
    protected static $logAttributesToIgnore = ['remember_token'];

    /**
     * 模型的连接名称
     *
     * @var string
     */
    protected $connection = 'mysql_admin';

    /**
     * 数据表名称
     *
     * @var string
     */
    protected $table = 'auth_users';

    /**
     * 字段【删除时间】
     *
     * @var string
     */
    const DELETED_AT = 'deleted';

    /**
     * 字段【创建时间】
     *
     * @var string
     */
    const CREATED_AT = 'created';

    /**
     * 字段【更新时间】
     *
     * @var string
     */
    const UPDATED_AT = 'updated';

    /**
     * 进行类型转换的字段
     *
     * @var array
     */
    protected $casts = [
        'deleted' => 'timestamp',
        'created' => 'timestamp',
        'updated' => 'timestamp',
        'email_verified' => 'timestamp'
    ];

    /**
     * 时间字段的保存格式【时间戳】
     *
     * @var string
     */
    protected $dateFormat = 'U';

    /**
     * 为数组/JSON序列化准备一个日期。
     *
     * @param \DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return Carbon::instance($date)->toDateTimeString();
    }

    /**
     * 可以批量赋值的字段
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    /**
     * 查询后隐藏的字段
     *
     * @var array
     */
    protected $hidden = [
        'is_super_admin',
        'password',
        'remember_token'
    ];

    /**
     * 排序【升序】
     */
    const ORDER_ASC = 'asc';

    /**
     * 排序【降序】
     */
    const ORDER_DESC = 'desc';

    /**
     * 用户个人配置
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function config()
    {
        return $this->hasOne(UserConfig::class, 'user_id', 'id');
    }
}
