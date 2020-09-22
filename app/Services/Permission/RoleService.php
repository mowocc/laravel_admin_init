<?php
namespace App\Services\Permission;

use Illuminate\Support\Str;
use App\Services\ServiceTrait;
use Spatie\Permission\Models\Role;

class RoleService
{
    use ServiceTrait;

    /*
     * =========================
     * --------------- 分级授权 ---------------
     * =========================
     */

    /**
     * 角色前缀【分级授权】
     *
     * @var string
     */
    const PREFIX_TREE = 'tree.';

    /**
     * 角色唯一key【分级授权】
     *
     * @param int $treeId
     * @return string
     */
    public function tree($treeId)
    {
        return Str::start($treeId, self::PREFIX_TREE);
    }

    /**
     * 查询或创建角色【分级授权】
     *
     * @param int $treeId
     * @return \Spatie\Permission\Models\Role
     */
    public function treeRole($treeId)
    {
        return Role::findOrCreate($this->tree($treeId));
    }

    /**
     * 角色同步多用户
     *
     * @param int $treeId
     * @param \Illuminate\Database\Eloquent\Collection $users
     * @return void
     */
    public function treeRoleSyncUsers($treeId, $users)
    {
        // 权限角色
        $role = $this->treeRole($treeId);
        // 角色多用户同步
        $role->users()->sync($users->pluck('id'));
    }

    /**
     * 角色分配多权限
     *
     * @param int $treeId
     * @param \Illuminate\Support\Collection $permissions
     * @return void
     */
    public function treeRoleAssignPermissions($treeId, $permissions)
    {
        // 权限角色
        $role = $this->treeRole($treeId);
        // 循环将权限分配给角色
        $permissions->map(function ($permission) use ($role) {
            $permission->assignRole($role);
        });
    }

    /**
     * 角色删除多权限
     *
     * @param int $treeId
     * @param \Illuminate\Support\Collection $permissions
     * @return void
     */
    public function treeRoleRemovePermissions($treeId, $permissions)
    {
        // 权限角色
        $role = $this->treeRole($treeId);
        // 循环从角色中删除权限
        $permissions->map(function ($permission) use ($role) {
            $permission->removeRole($role);
        });
    }
}
