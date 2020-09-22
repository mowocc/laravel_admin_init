<?php
namespace App\Observers\Admin\Config;

use App\Models\Admin\Config\Menu;
use App\Services\System\Setting\MenuActionService;

class MenuObserver
{

    /**
     * 处理「saved」事件
     *
     * @param \App\Models\Admin\Config\Menu $menu            
     * @return void
     */
    public function saved(Menu $menu)
    {
        if ($menu->isDirty('route_name')) {
            MenuActionService::create()->updateMenuAction($menu);
        }
    }
}