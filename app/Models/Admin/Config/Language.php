<?php
namespace App\Models\Admin\Config;

use App\Models\Admin\Model;
use App\Models\Traits\Admin\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
{
    use SoftDeletes, LogsActivity;

    /**
     * 数据表名称
     *
     * @var string
     */
    protected $table = 'config_languages';

    /**
     * 进行类型转换的字段
     *
     * @var array
     */
    protected $casts = [
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
        'lang'
    ];
}
