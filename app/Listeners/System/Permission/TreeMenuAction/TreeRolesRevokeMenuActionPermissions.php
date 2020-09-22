<?php
namespace App\Listeners\System\Permission\TreeMenuAction;

use App\Events\System\Permission\TreeMenuAction\Updated;
use App\Models\Admin\Auth\Permission\Tree;
use App\Models\Admin\Auth\Permission\TreeMenuAction;
use App\Services\Permission\RoleService;
use App\Services\Permission\PermissionService;

class TreeRolesRevokeMenuActionPermissions
{

    /**
     * 角色(授权树)递归回收授权页面功能
     *
     * @param Updated $event            
     * @return void
     */
    public function handle(Updated $event)
    {
        if (! count($event->menuActionsDetach)) {
            return null;
        }
        
        $roleService = new RoleService();
        $permissionService = new PermissionService();
        
        // 所有子孙节点
        $trees = Tree::selectChildById($event->tree->id);
        
        // 需要回收的父级节点
        $detachParentIds = [$event->tree->id];
        
        // 循环子孙节点
        foreach ($trees as $tree) {
            // 判断是否回收
            if (! in_array($tree->parent_id, $detachParentIds)) {
                continue;
            }
            
            // 页面功能
            $menuActions = $tree->menuActions->pluck('menu_action')
                ->intersect($event->menuActionsDetach)
                ->all();
            
            // 判断是否回收
            if (! count($menuActions)) {
                continue;
            }
            
            // 制作页面功能权限
            $permissions = $permissionService->menuActionPermissions($menuActions);
            // 角色删除多权限
            $roleService->treeRoleRemovePermissions($tree->id, $permissions);
            
            // 标记回收节点
            array_push($detachParentIds, $tree->id);
        }
        
        // 回收全部关联【子孙节点】
        TreeMenuAction::whereIn('tree_id', $trees->pluck('id')->all())->whereIn('menu_action', $event->menuActionsDetach)->delete();
    }
}
