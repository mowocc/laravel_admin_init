<?php
namespace App\Services\System\Permission;

use Illuminate\Support\Arr;
use App\Services\ServiceTrait;
use App\Models\Admin\Config\Menu;
use App\Models\Admin\Auth\Permission\Tree;

class TreeMenuService
{
    use ServiceTrait;

    /**
     * 获取授权页面集合
     *
     * @param int $treeId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMenus($treeId)
    {
        if ($treeId == 0) {
            return Menu::selectAll();
        }

        return Tree::findOrFail($treeId) ->menus()
            ->orderBy('parent_id', 'asc')
            ->orderBy('sort', 'asc')
            ->get();
    }

    /**
     * 更新授权页面
     *
     * @param int $treeId
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function update($treeId, $request)
    {
        // 事物回滚
        return Tree::dbConnection()->transaction(function () use ($treeId, $request) {
            // 查询授权树
            $tree = Tree::findOrFail($treeId);

            // 路由名称【之前的】
            $routeNamesOld = $tree->menus->pluck('route_name')->all();
            // 路由名称【之后的】
            $routeNamesNew = Arr::wrap($request->input('menus.route_names'));

            // 路由名称【移除关联】
            $routeNamesDetach = array_diff($routeNamesOld, $routeNamesNew);
            // 路由名称【添加关联】
            $routeNamesAttach = array_diff($routeNamesNew, $routeNamesOld);

            // 移除关联
            $tree->menus()->detach($routeNamesDetach);
            // 添加关联
            $tree->menus()->attach($routeNamesAttach);

            // 授权页面更新完成
            \App\Events\System\Permission\TreeMenu\Updated::dispatch($tree, $routeNamesDetach, $routeNamesAttach);

            // 动态属性已经加载，更新后需重新加载
            return $tree->menus()->get()->sortBy('parent_id')->sortBy('sort');
        });
    }
}
