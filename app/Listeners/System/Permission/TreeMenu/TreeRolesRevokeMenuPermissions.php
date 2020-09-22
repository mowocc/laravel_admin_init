<?php
namespace App\Listeners\System\Permission\TreeMenu;

use App\Events\System\Permission\TreeMenu\Updated;
use App\Models\Admin\Auth\Permission\Tree;
use App\Models\Admin\Auth\Permission\TreeMenu;
use App\Services\Permission\RoleService;
use App\Services\Permission\PermissionService;

class TreeRolesRevokeMenuPermissions
{

    /**
     * 角色(授权树)递归回收授权页面
     *
     * @param Updated $event            
     * @return void
     */
    public function handle(Updated $event)
    {
        if (! count($event->routeNamesDetach)) {
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
            
            // 页面路由名称
            $routeNames = $tree->menus->pluck('route_name')
                ->intersect($event->routeNamesDetach)
                ->all();
            
            // 判断是否回收
            if (! count($routeNames)) {
                continue;
            }
            
            // 制作页面权限
            $permissions = $permissionService->menuPermissions($routeNames);
            // 角色删除多权限
            $roleService->treeRoleRemovePermissions($tree->id, $permissions);
            
            // 标记回收节点
            array_push($detachParentIds, $tree->id);
        }
        
        // 回收全部关联【子孙节点】
        TreeMenu::whereIn('tree_id', $trees->pluck('id')->all())->whereIn('menu_route_name', $event->routeNamesDetach)->delete();
    }
}
