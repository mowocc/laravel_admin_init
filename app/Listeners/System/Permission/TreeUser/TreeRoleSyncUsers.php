<?php
namespace App\Listeners\System\Permission\TreeUser;

use App\Events\System\Permission\TreeUser\Updated;
use App\Services\Permission\RoleService;

class TreeRoleSyncUsers
{

    /**
     * 角色(授权树)同步授权用户
     *
     * @param Updated $event            
     * @return void
     */
    public function handle(Updated $event)
    {
        RoleService::create()->treeRoleSyncUsers($event->tree->id, $event->tree->users);
    }
}
