<?php
namespace App\Services\System\Permission;

use Illuminate\Support\Arr;
use App\Services\ServiceTrait;
use App\Models\Admin\Config\MenuAction;
use App\Models\Admin\Auth\Permission\Tree;

class TreeMenuActionService
{
    use ServiceTrait;

    /**
     * 获取授权页面功能集合
     *
     * @param int $treeId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMenuActions($treeId)
    {
        if ($treeId == 0) {
            return MenuAction::all();
        }

        return Tree::findOrFail($treeId)->menuActions;
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

            // 页面功能【之前的】
            $menuActionsOld = $tree->menuActions->pluck('menu_action')->all();
            // 页面功能【之后的】
            $menuActionsNew = Arr::collapse(Arr::wrap($request->input('menu_actions')));
            
            // 页面功能【移除关联】
            $menuActionsDetach = array_diff($menuActionsOld, $menuActionsNew);
            // 页面功能【添加关联】
            $menuActionsAttach = array_diff($menuActionsNew, $menuActionsOld);

            // 移除关联
            $tree->menuActions()->detach($menuActionsDetach);
            // 添加关联
            $tree->menuActions()->attach($menuActionsAttach);

            // 授权页面更新完成
            \App\Events\System\Permission\TreeMenuAction\Updated::dispatch($tree, $menuActionsDetach, $menuActionsAttach);

            // 动态属性已经加载，更新后需重新加载
            return $tree->menuActions()->get();
        });
    }
}
