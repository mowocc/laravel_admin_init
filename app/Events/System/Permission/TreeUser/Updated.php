<?php
namespace App\Events\System\Permission\TreeUser;

use Illuminate\Foundation\Events\Dispatchable;

class Updated
{
    use Dispatchable;

    /**
     * 授权树
     *
     * @var \App\Models\Admin\Auth\Permission\Tree
     */
    public $tree;

    /**
     * 授权用户更新完成
     *
     * @param \App\Models\Admin\Auth\Permission\Tree $tree            
     * @return void
     */
    public function __construct($tree)
    {
        $this->tree = $tree;
    }
}
