<?php
namespace App\Models\Admin;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use App\Models\Traits\DatabaseBuilder;

class Model extends EloquentModel
{
    use DatabaseBuilder;

    /**
     * 模型的连接名称
     *
     * @var string
     */
    protected $connection = 'mysql_admin';

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
        'updated' => 'timestamp'
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
     * 排序【升序】
     */
    const ORDER_ASC = 'asc';

    /**
     * 排序【降序】
     */
    const ORDER_DESC = 'desc';
}
