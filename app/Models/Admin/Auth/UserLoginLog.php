<?php
namespace App\Models\Admin\Auth;

use App\Models\Admin\Model;

class UserLoginLog extends Model
{

    /**
     * 数据表名称
     *
     * @var string
     */
    protected $table = 'auth_user_login_log';

    /**
     * 进行类型转换的字段
     *
     * @var array
     */
    protected $casts = [
        'languages' => 'array',
        'lat' => 'double',
        'lon' => 'double',
        'created' => 'timestamp',
        'updated' => 'timestamp'
    ];

    /**
     * 可以批量赋值的字段
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'languages',
        'device',
        'platform',
        'platform_version',
        'browser',
        'browser_version',
        'robot',
        'ip',
        'iso_code',
        'country',
        'city',
        'state',
        'state_name',
        'postal_code',
        'lat',
        'lon',
        'timezone',
        'continent',
        'currency',
        'default'
    ];

    /**
     * 获得拥有此日志的用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
