<?php
namespace App\Http\Resources\Admin;

use App\Http\Resources\Resource;
use App\Http\Resources\Traits\Attributes;
use App\Http\Resources\Common\TreeResource;
use App\Models\Admin\Config\Menu;

class MenuResource extends Resource
{
    use Attributes;

    /**
     * 页面功能
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected $menuActions;

    /**
     * 设置页面功能
     *
     * @param \Illuminate\Database\Eloquent\Collection $menuActions            
     * @return $this
     */
    public function setMenuAction($menuActions)
    {
        $this->menuActions = $menuActions;
        
        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request            
     * @return array
     */
    public function toArray($request)
    {
        $routeNames = $this->resource->pluck('route_name')->all();
        
        $routeNamesLeaf = Menu::makeMenusLeaf($this->resource)->pluck('route_name')->all();
        
        $menuActions = $this->menuActions->pluck('menu_action')->all();
        
        $this->fillAttributes([
            'menus' => TreeResource::make($this->resource)->toTree($request),
            'routeNames' => $routeNames,
            'routeNamesLeaf' => $routeNamesLeaf,
            'menuActions' => $menuActions
        ]);
        
        return $this->getResponse()->setResponseData($this->getAttributes());
    }
}
