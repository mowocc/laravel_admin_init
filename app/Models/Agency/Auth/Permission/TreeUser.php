<?php
namespace App\Models\Agency\Auth\Permission;

use App\Models\Agency\Pivot;

class TreeUser extends Pivot
{

    /**
     * 数据表名称
     *
     * @var string
     */
    protected $table = 'auth_permission_tree_has_users';
}
