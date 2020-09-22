<?php
namespace App\Mappers\Agency\Contacts;

use App\Mappers\DataMapper;
use App\Services\Auth\UserService as AuthUserService;

class UserMapper extends DataMapper
{

    public function toArray()
    {
        // 代理
        if ($this->request->filled('org_id')) {
            $this->offsetSet('org_id', $this->request->input('org_id'));
        }
        // 姓名
        $this->offsetSet('name', $this->request->input('name'));
        // 邮箱
        $this->offsetSet('email', $this->request->input('email'));
        // 密码
        if ($this->request->filled('password')) {
            $this->offsetSet('password', AuthUserService::create()->makeUserPassword($this->request->input('password')));
        }
        
        return $this->getArrayCopy();
    }
}