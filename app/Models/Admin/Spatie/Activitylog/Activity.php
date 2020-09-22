<?php
namespace App\Models\Admin\Spatie\Activitylog;

use Illuminate\Support\Carbon;
use Spatie\Activitylog\Models\Activity as SpatieActivity;

class Activity extends SpatieActivity
{

    /**
     * 查询后隐藏的字段
     *
     * @var array
     */
    protected $hidden = [
        'properties',
        'created_at',
        'updated_at'
    ];

    /**
     * 进行类型转换的字段
     *
     * @var array
     */
    protected $casts = [
        'properties' => 'collection',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp'
    ];

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
