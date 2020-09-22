<?php
namespace App\Models\Traits;

trait SuperAdmin
{

    /**
     * 超级管理员【是】
     *
     * @var integer
     */
    public static $superAdminTrue = 1;

    /**
     * 超级管理员【否】
     *
     * @var integer
     */
    public static $superAdminFalse = 0;

    /**
     * 用户角色验证【超级管理员】
     *
     * @return boolean
     */
    public function isSuperAdmin()
    {
        return (boolean) $this->is_super_admin;
    }

    /**
     * 用户分配角色【超级管理员】
     *
     * @return $this
     */
    public function assignSuperAdmin()
    {
        $this->is_super_admin = static::$superAdminTrue;
        
        $this->save();
        
        return $this;
    }

    /**
     * 用户删除角色【超级管理员】
     *
     * @return $this
     */
    public function removeSuperAdmin()
    {
        $this->is_super_admin = static::$superAdminFalse;
        
        $this->save();
        
        return $this;
    }
}
