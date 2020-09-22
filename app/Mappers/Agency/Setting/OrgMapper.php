<?php
namespace App\Mappers\Agency\Setting;

use App\Mappers\DataMapper;

class OrgMapper extends DataMapper
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
        // 名称
        $this->offsetSet('name', $this->request->input('name'));
        
        return $this->getArrayCopy();
    }
}
