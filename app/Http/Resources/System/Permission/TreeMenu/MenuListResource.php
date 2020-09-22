<?php
namespace App\Http\Resources\System\Permission\TreeMenu;

use App\Http\Resources\Resource;
use App\Http\Resources\Traits\Attributes;
use App\Http\Resources\Common\TreeResource;

class MenuListResource extends Resource
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
        $menuActions = $this->menuActions->mapToGroups(function ($item, $key) {
            return [
                $item['menu_id'] => $item['menu_action']
            ];
        });
        
        $this->fillAttributes([
            'menuList' => TreeResource::make($this->resource)->toTree($request),
            'menuActionList' => $this->menuActions->groupBy('menu_id'),
            'menuActions' => $menuActions
        ]);
        
        return $this->getResponse()->setResponseData($this->getAttributes());
    }
}
