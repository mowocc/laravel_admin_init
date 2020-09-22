<?php
namespace App\Models\Admin\Auth\Permission;

use App\Models\Admin\Pivot;

class TreeMenu extends Pivot
{

    /**
     * 数据表名称
     *
     * @var string
     */
    protected $table = 'auth_permission_tree_has_menus';
}
