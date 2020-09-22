<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Relations\Pivot as EloquentPivot;
use App\Models\Traits\DatabaseBuilder;

class Pivot extends EloquentPivot
{
    use DatabaseBuilder;

    /**
     * 模型的连接名称
     *
     * @var string
     */
    protected $connection = 'mysql_admin';
}
