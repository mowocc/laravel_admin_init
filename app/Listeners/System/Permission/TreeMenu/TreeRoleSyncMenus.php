<?php
namespace App\Listeners\System\Permission\TreeMenu;

use App\Events\System\Permission\TreeMenu\Updated;
use App\Services\Permission\RoleService;
use App\Services\Permission\PermissionService;

class TreeRoleSyncMenus
{

    /**
     * 角色(授权树)同步授权页面
     *
     * @param Updated $event            
     * @return void
     */
    public function handle(Updated $event)
    {
        if (! count($event->routeNamesDetach) && ! count($event->routeNamesAttach)) {
            return null;
        }
        
        $roleService = new RoleService();
        $permissionService = new PermissionService();
        
        // 制作页面权限
        $permissions = $permissionService->menuPermissions($event->routeNamesDetach);
        // 角色删除多权限
        $roleService->treeRoleRemovePermissions($event->tree->id, $permissions);
        
        // 制作页面权限
        $permissions = $permissionService->menuPermissions($event->routeNamesAttach);
        // 角色分配多权限
        $roleService->treeRoleAssignPermissions($event->tree->id, $permissions);
    }
}
