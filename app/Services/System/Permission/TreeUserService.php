<?php
namespace App\Services\System\Permission;

use App\Services\ServiceTrait;
use App\Models\Admin\Auth\Permission\Tree;
use Illuminate\Support\Arr;

class TreeUserService
{
    use ServiceTrait;
    
    /**
     * 获取授权用户集合
     *
     * @param int $treeId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUsers($treeId)
    {
        return Tree::findOrFail($treeId)->users;
    }
    
    /**
     * 更新授权用户
     *
     * @param int $treeId
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function update($treeId, $request)
    {
        // 事物回滚
        return Tree::dbConnection()->transaction(function () use ($treeId, $request) {
            // 查询授权树
            $tree = Tree::findOrFail($treeId);
            
            // 同步数据
            $tree->users()->sync(Arr::wrap($request->input('user_ids')));

            // 授权用户更新完成
            \App\Events\System\Permission\TreeUser\Updated::dispatch($tree);

            return $tree->users;
        });
    }
}
