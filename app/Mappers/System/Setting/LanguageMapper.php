<?php
namespace App\Mappers\System\Setting;

use App\Mappers\DataMapper;

class LanguageMapper extends DataMapper
{

    public function toArray()
    {
        // 语言名称
        $this->offsetSet('name', $this->request->input('name'));
        // 语言code
        $this->offsetSet('lang', $this->request->input('lang'));
        
        return $this->getArrayCopy();
    }
}
