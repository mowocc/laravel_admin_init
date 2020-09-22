<?php
namespace App\Listeners\System\Permission\TreeMenuAction;

use App\Events\System\Permission\TreeMenuAction\Updated;
use App\Services\Permission\RoleService;
use App\Services\Permission\PermissionService;

class TreeRoleSyncMenuActions
{

    /**
     * 角色(授权树)同步授权页面功能
     *
     * @param Updated $event            
     * @return void
     */
    public function handle(Updated $event)
    {
        if (! count($event->menuActionsDetach) && ! count($event->menuActionsAttach)) {
            return null;
        }
        
        $roleService = new RoleService();
        $permissionService = new PermissionService();
        
        // 制作页面功能权限
        $permissions = $permissionService->menuActionPermissions($event->menuActionsDetach);
        // 角色删除多权限
        $roleService->treeRoleRemovePermissions($event->tree->id, $permissions);
        
        // 制作页面功能权限
        $permissions = $permissionService->menuActionPermissions($event->menuActionsAttach);
        // 角色分配多权限
        $roleService->treeRoleAssignPermissions($event->tree->id, $permissions);
    }
}
