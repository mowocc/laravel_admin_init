<?php
namespace App\Listeners\System\Permission;

use App\Events\System\Permission\TreeDeleting;
use App\Services\Permission\RoleService;

class TreeRoleRemove
{

    /**
     * 删除角色(授权树)
     *
     * @param TreeDeleting $event            
     * @return void
     */
    public function handle(TreeDeleting $event)
    {
        RoleService::create()->treeRole($event->tree->id)->delete();
    }
}
