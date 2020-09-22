<?php
namespace App\Http\Resources\System\Setting\MenuAction;

use App\Http\Resources\Resource;
use App\Http\Resources\Traits\Attributes;
use App\Http\Resources\Traits\Paginate;

class DatasResource extends Resource
{
    use Attributes, Paginate;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request            
     * @return array
     */
    public function toArray($request)
    {
        $this->setAttribute('data', parent::toArray($request));
        
        $actions = $this->resource->groupBy('type')->toArray();
        
        $actions['system'] = $actions['system'] ?? [];
        $actions['custom'] = $actions['custom'] ?? [];
        
        $this->setAttribute('actions', $actions);
        
        return $this->getResponse()->setResponseData($this->getAttributes());
    }
}
