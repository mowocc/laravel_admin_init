<?php
namespace App\Services\Auth;

use App\Services\ServiceTrait;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\Response\ServiceException;
use App\Models\Admin\Auth\User;

class UserService
{
    use ServiceTrait;

    /**
     * 制作用户密码
     *
     * @param string $password            
     * @return string
     */
    public function makeUserPassword($password)
    {
        return Hash::make($password);
    }

    /**
     * 验证用户密码
     *
     * @param \App\Models\Admin\Auth\User $user            
     * @param string $password            
     * @throws ServiceException
     * @return $this
     */
    public function verifyUserPassword($user, $password)
    {
        if (! Hash::check($password, $user->password)) {
            throw new ServiceException(ServiceException::SERVICE_PARAM_INVALID_PASSWORD);
        }
        return $this;
    }

    /**
     * 验证邮箱已存在（已存在则抛出异常）
     *
     * @param string $email            
     * @param int $selfId            
     * @throws ServiceException
     * @return $this
     */
    public function verifyUserExistedByEmail($email, $selfId = null)
    {
        $user = User::withTrashed()->where('email', $email)->first();
        
        if (! $selfId && $user) {
            throw new ServiceException(ServiceException::SERVICE_EXISTED_EMAIL);
        } elseif ($selfId && $user && $selfId != $user->id) {
            throw new ServiceException(ServiceException::SERVICE_EXISTED_EMAIL);
        }
        return $this;
    }

    /**
     * 验证邮箱不存在（不存在则抛出异常）
     *
     * @param string $email            
     * @throws ServiceException
     * @return $this
     */
    public function verifyUserNotExistByEmail($email)
    {
        if (User::withTrashed()->where('email', $email)->doesntExist()) {
            throw new ServiceException(ServiceException::SERVICE_NOT_EXIST_EMAIL);
        }
        return $this;
    }

    /**
     * 更新用户密码
     *
     * @param \App\Models\Admin\Auth\User $user    
     * @param string $password            
     * @param string $passwordCurrent            
     * @return \App\Models\Admin\Auth\User
     */
    public function updatePassword($user, $password, $passwordCurrent)
    {
        // 验证密码【当前】
        $this->verifyUserPassword($user, $passwordCurrent);
        
        // 修改密码
        $user->password = $this->makeUserPassword($password);
        // 保存密码
        $user->save();
        
        return $user;
    }
}