<?php
namespace App\Events\System\Permission\TreeMenu;

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
     * 路由名称【移除关联】
     *
     * @var array
     */
    public $routeNamesDetach;

    /**
     * 路由名称【添加关联】
     *
     * @var array
     */
    public $routeNamesAttach;

    /**
     * 授权页面更新完成
     *
     * @param \App\Models\Admin\Auth\Permission\Tree $tree            
     * @param array $routeNamesDetach            
     * @param array $routeNamesAttach            
     * @return void
     */
    public function __construct($tree, $routeNamesDetach, $routeNamesAttach)
    {
        $this->tree = $tree;
        $this->routeNamesDetach = $routeNamesDetach;
        $this->routeNamesAttach = $routeNamesAttach;
    }
}
