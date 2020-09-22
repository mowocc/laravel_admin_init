<?php
namespace App\Models\Agency;

use Illuminate\Database\Eloquent\Relations\MorphPivot as EloquentMorphPivot;
use App\Models\Traits\DatabaseBuilder;

class MorphPivot extends EloquentMorphPivot
{
    use DatabaseBuilder;

    /**
     * 模型的连接名称
     *
     * @var string
     */
    protected $connection = 'mysql_agency';
}
