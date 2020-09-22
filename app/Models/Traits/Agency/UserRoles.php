<?php
namespace App\Models\Traits\Agency;

use App\Models\Agency\Auth\Permission\Tree;
use App\Models\Agency\Auth\Permission\TreeUser;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait UserRoles
{

    /**
     * 用户拥有的角色【授权树】
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function treeRoles(): BelongsToMany
    {
        return $this->belongsToMany(Tree::class, TreeUser::tableName(), 'user_id', 'tree_id');
    }
}
