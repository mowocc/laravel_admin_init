<?php
namespace App\Services\System\Setting;

use App\Services\Service;
use App\Mappers\System\Setting\MenuActionMapper;
use App\Models\Admin\Config\MenuAction;
use App\Models\Admin\Config\Menu;

class MenuActionService extends Service
{

    /**
     * 获取数据列表
     *
     * @param int $menuId            
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getList($menuId)
    {
        return Menu::withTrashed()->findOrFail($menuId)->actions;
    }

    /**
     * 保存数据
     *
     * @param int $menuId            
     * @param \Illuminate\Http\Request $request            
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function save($menuId, $request)
    {
        // 事物回滚
        return MenuAction::dbConnection()->transaction(function () use ($menuId, $request) {
            // 目录菜单
            $menu = Menu::withTrashed()->findOrFail($menuId);
            
            // 制作数据
            $actions = MenuActionMapper::make($request)->setMenu($menu)->toArray();
            
            // 删除数据
            $menu->actions()->delete();
            // 保存数据
            $menu->actions()->createMany($actions);
            
            return $menu->actions;
        });
    }
    
    /**
     * 更新页面功能[menu_action]
     * 
     * @param \App\Models\Admin\Config\Menu $menu
     */
    public function updateMenuAction($menu)
    {
        foreach ($menu->actions as $action) {
            $action->menu_action = $menu->route_name . '.' . $action->action;
            $action->save();
        }
    }
}
