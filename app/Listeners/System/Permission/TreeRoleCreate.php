<?php
namespace App\Listeners\System\Permission;

use App\Events\System\Permission\TreeCreated;
use App\Services\Permission\RoleService;

class TreeRoleCreate
{

    /**
     * 创建角色(授权树)
     *
     * @param TreeCreated $event            
     * @return void
     */
    public function handle(TreeCreated $event)
    {
        RoleService::create()->treeRole($event->tree->id);
    }
}
