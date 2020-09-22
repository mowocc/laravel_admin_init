<?php
namespace App\Models\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait DatabaseBuilder
{

    /**
     * 初始化并返回数据库连接
     *
     * @return \Illuminate\Database\ConnectionInterface
     */
    protected function dbConnection()
    {
        return DB::connection($this->getConnectionName());
    }

    /**
     * 初始化并返回数据库操作
     *
     * @return \Illuminate\Database\Schema\Builder
     */
    protected function schemaConnection()
    {
        return Schema::connection($this->getConnectionName());
    }

    /**
     * 获取数据表名称
     *
     * @param string $alias            
     * @return string
     */
    protected function tableName($alias = null)
    {
        return empty($alias) ? $this->getTable() : $this->getTable() . ' as ' . $alias;
    }

    /**
     * 初始化并返回数据库查询构建器
     *
     * @param string $alias            
     * @return \Illuminate\Database\Query\Builder
     */
    protected function dbTable($alias = null)
    {
        return $this->dbConnection()->table($this->tableName($alias));
    }
}
