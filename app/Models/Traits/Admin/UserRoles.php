<?php
namespace App\Models\Traits\Admin;

use App\Models\Admin\Auth\Permission\Tree;
use App\Models\Admin\Auth\Permission\TreeUser;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait UserRoles
{

    /**
     * 用户拥有的角色【授权树】
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function treeRoles(): BelongsToMany
    {
        return $this->belongsToMany(Tree::class, TreeUser::tableName(), 'user_id', 'tree_id');
    }

    /**
     * 判断用户是否拥有权限【授权树】【目录菜单】
     *
     * @return boolean
     */
    public function isTreePermissionMenus()
    {
        // 授权页面计数
        return !! $this->treeRoles->loadCount('menus')
        // 页面存在判断
        ->first(function ($role) {
            return $role->menus_count;
        });
    }

    /**
     * 用户拥有的权限【授权树】【目录菜单】
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTreePermissionMenus()
    {
        $menus = collect();

        // 加载授权页面
        $this->treeRoles->loadMissing('menus')
        // 合并授权页面
        ->each(function ($role) use ($menus) {
            $menus->push(...$role->menus->makeHidden('pivot'));
        });

        return $menus->unique('id')->sortBy('parent_id')->sortBy('sort');
    }

    /**
     * 用户拥有的权限【授权树】【页面功能】
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTreePermissionMenuActions()
    {
        $menuActions = collect();

        // 加载授权页面功能
        $this->treeRoles->loadMissing('menuActions')
        // 合并授权页面功能
        ->each(function ($role) use ($menuActions) {
            $menuActions->push(...$role->menuActions->makeHidden('pivot'));
        });

        return $menuActions->unique('id');
    }
}
