<?php
namespace App\Services\Permission;

use Illuminate\Support\Str;
use App\Services\ServiceTrait;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    use ServiceTrait;

    /*
     * =========================
     * --------------- 目录菜单 ---------------
     * =========================
     */

    /**
     * 权限前缀【目录菜单】
     *
     * @var string
     */
    const PREFIX_MENU = 'menu.';

    /**
     * 权限唯一key【目录菜单】
     *
     * @param string $routeName
     * @return string
     */
    public function menu($routeName)
    {
        return Str::start($routeName, self::PREFIX_MENU);
    }

    /**
     * 权限唯一key【目录菜单】【多条】
     *
     * @param array $routeNames
     * @return \Illuminate\Support\Collection
     */
    public function menus($routeNames)
    {
        return collect($routeNames)->map(function ($routeName) {
            return $this->menu($routeName);
        });
    }

    /**
     * 查询或创建权限【目录菜单】
     *
     * @param string $routeName
     * @return \Spatie\Permission\Models\Permission
     */
    public function menuPermission($routeName)
    {
        return Permission::findOrCreate($this->menu($routeName));
    }

    /**
     * 查询或创建权限【目录菜单】【多条】
     *
     * @param array $routeNames
     * @return \Illuminate\Support\Collection
     */
    public function menuPermissions($routeNames)
    {
        return collect($routeNames)->map(function ($routeName) {
            return $this->menuPermission($routeName);
        });
    }

    /*
     * =========================
     * --------------- 页面功能 ---------------
     * =========================
     */

    /**
     * 权限前缀【页面功能】
     *
     * @var string
     */
    const PREFIX_MENU_ACTION = 'menuAction.';

    /**
     * 权限唯一key【页面功能】
     *
     * @param string $menuAction
     * @return string
     */
    public function menuAction($menuAction)
    {
        return Str::start($menuAction, self::PREFIX_MENU_ACTION);
    }

    /**
     * 权限唯一key【页面功能】【多条】
     *
     * @param array $menuActions
     * @return \Illuminate\Support\Collection
     */
    public function menuActions($menuActions)
    {
        return collect($menuActions)->map(function ($menuAction) {
            return $this->menuAction($menuAction);
        });
    }

    /**
     * 查询或创建权限【页面功能】
     *
     * @param string $menuAction
     * @return \Spatie\Permission\Models\Permission
     */
    public function menuActionPermission($menuAction)
    {
        return Permission::findOrCreate($this->menuAction($menuAction));
    }

    /**
     * 查询或创建权限【页面功能】【多条】
     *
     * @param array $menuActions
     * @return \Illuminate\Support\Collection
     */
    public function menuActionPermissions($menuActions)
    {
        return collect($menuActions)->map(function ($menuAction) {
            return $this->menuActionPermission($menuAction);
        });
    }
}
