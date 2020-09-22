<?php
namespace App\Mappers\System\Setting;

use App\Mappers\DataMapper;
use Illuminate\Support\Arr;

class ServerMapper extends DataMapper
{

    public function toArray()
    {
        // 服务器分区名称
        $this->offsetSet('name', $this->request->input('name'));
        // mysql连接配置
        $this->offsetSet('mysql_agency', Arr::wrap($this->request->input('mysql_agency')));
        
        return $this->getArrayCopy();
    }
}