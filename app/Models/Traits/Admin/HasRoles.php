<?php
namespace App\Models\Traits\Admin;

use Spatie\Permission\Traits\HasRoles as SpatieHasRoles;

trait HasRoles
{
    use SpatieHasRoles;

    /**
     * 权限管理守卫
     *
     * @return string
     */
    public function guardName()
    {
        return 'sanctum';
    }
}
