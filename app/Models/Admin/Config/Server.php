<?php
namespace App\Models\Admin\Config;

use App\Models\Admin\Model;
use App\Models\Traits\Admin\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Server extends Model
{
    use SoftDeletes, LogsActivity;

    /**
     * 数据表名称
     *
     * @var string
     */
    protected $table = 'config_servers';

    /**
     * 进行类型转换的字段
     *
     * @var array
     */
    protected $casts = [
        'mysql_agency' => 'array',
        'created' => 'timestamp',
        'updated' => 'timestamp'
    ];

    /**
     * 可以批量赋值的字段
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'mysql_agency'
    ];

    /**
     * 查询后隐藏的字段
     *
     * @var array
     */
    protected $hidden = [
        'mysql_agency'
    ];
}
