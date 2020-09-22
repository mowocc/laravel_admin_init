<?php
namespace App\Models\Agency\Auth;

use App\Models\Agency\Model;
use App\Models\Agency\Agency\Org;
use App\Models\Traits\Agency\LogsActivity;
use App\Models\Traits\Agency\UserRoles;
use App\Models\Traits\SuperAdmin;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use SoftDeletes, LogsActivity, SuperAdmin, UserRoles;

    /**
     * 数据表名称
     *
     * @var string
     */
    protected $table = 'auth_users';

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
     * 可以批量赋值的字段
     *
     * @var array
     */
    protected $fillable = [
        'org_id',
        'name',
        'email',
        'password'
    ];

    /**
     * 所属代理平台
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function org()
    {
        return $this->belongsTo(Org::class, 'org_id', 'id');
    }
}
