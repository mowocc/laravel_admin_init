<?php
namespace App\Mappers\System\Setting;

use App\Mappers\DataMapper;

class MenuMapper extends DataMapper
{

    public function toArray()
    {
        // 排序
        if ($this->request->filled('sort')) {
            $this->offsetSet('sort', $this->request->input('sort'));
        }
        // 父ID
        if ($this->request->filled('parent_id')) {
            $this->offsetSet('parent_id', $this->request->input('parent_id'));
        }
        // 菜单名称
        $this->offsetSet('name', $this->request->input('name'));
        // font 图标名称
        $this->offsetSet('icon', $this->request->input('icon'));
        // 前端路由
        $this->offsetSet('route_path', $this->request->input('route_path'));
        // 前端路由名称
        $this->offsetSet('route_name', $this->request->input('route_name'));
        
        return $this->getArrayCopy();
    }
}