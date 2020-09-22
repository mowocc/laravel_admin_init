<?php
namespace App\Mappers\System\Setting;

use App\Mappers\DataMapper;
use App\Models\Admin\Config\MenuAction;

class MenuActionMapper extends DataMapper
{

    /**
     * 目录菜单
     *
     * @var \App\Models\Admin\Config\Menu
     */
    protected $menu;

    /**
     * 设置目录菜单
     *
     * @param \App\Models\Admin\Config\Menu $menu            
     * @return $this
     */
    public function setMenu($menu)
    {
        $this->menu = $menu;
        
        return $this;
    }

    /**
     * 将请求转换为数组
     *
     * @return array
     */
    public function toArray()
    {
        // 系统
        foreach ($this->request->input('actions.system', []) as $item) {
            $this->append([
                'menu_id' => $this->request->input('menu_id'),
                'menu_action' => $this->menu->route_name . '.' . $item['action'],
                'action' => $item['action'],
                'name' => $item['name'],
                'type' => MenuAction::TYPE_SYSTEM
            ]);
        }
        
        // 自定义
        foreach ($this->request->input('actions.custom', []) as $item) {
            $this->append([
                'menu_id' => $this->request->input('menu_id'),
                'menu_action' => $this->menu->route_name . '.' . $item['action'],
                'action' => $item['action'],
                'name' => $item['name'],
                'type' => MenuAction::TYPE_CUSTOM
            ]);
        }
        
        return $this->getArrayCopy();
    }
}