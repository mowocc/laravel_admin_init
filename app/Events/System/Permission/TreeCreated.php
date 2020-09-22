<?php
namespace App\Events\System\Permission;

use Illuminate\Foundation\Events\Dispatchable;

class TreeCreated
{
    use Dispatchable;

    /**
     * 分级授权树
     *
     * @var \App\Models\Admin\Auth\Permission\Tree
     */
    public $tree;

    /**
     * 分级授权树创建完成
     *
     * @param \App\Models\Admin\Auth\Permission\Tree $tree            
     * @return void
     */
    public function __construct($tree)
    {
        $this->tree = $tree;
    }
}
