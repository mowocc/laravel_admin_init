<?php
namespace App\Models\Traits\Admin;

use Spatie\Activitylog\Traits\LogsActivity as SpatieLogsActivity;

trait LogsActivity
{
    use SpatieLogsActivity;

    /**
     * 【活动日志】活动名称
     *
     * @var string
     */
    protected static $logName = 'model.events.admin';

    /**
     * 【活动日志】记录的属性
     *
     * @var array
     */
    protected static $logAttributes = ['*'];

    /**
     * 【活动日志】仅记录更改的属性
     *
     * @var boolean
     */
    protected static $logOnlyDirty = true;

    /**
     * 【活动日志】不记录没有更改属性的空保存
     *
     * @var boolean
     */
    protected static $submitEmptyLogs = false;
}
