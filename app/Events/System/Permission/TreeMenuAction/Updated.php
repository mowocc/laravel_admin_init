<?php
namespace App\Events\System\Permission\TreeMenuAction;

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
     * 页面功能【移除关联】
     *
     * @var array
     */
    public $menuActionsDetach;

    /**
     * 页面功能【添加关联】
     *
     * @var array
     */
    public $menuActionsAttach;

    /**
     * 授权页面功能更新完成
     *
     * @param \App\Models\Admin\Auth\Permission\Tree $tree            
     * @param array $menuActionsDetach            
     * @param array $menuActionsAttach            
     * @return void
     */
    public function __construct($tree, $menuActionsDetach, $menuActionsAttach)
    {
        $this->tree = $tree;
        $this->menuActionsDetach = $menuActionsDetach;
        $this->menuActionsAttach = $menuActionsAttach;
    }
}
