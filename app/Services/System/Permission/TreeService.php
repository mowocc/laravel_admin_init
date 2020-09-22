<?php
namespace App\Services\System\Permission;

use App\Services\ServiceTrait;
use App\Exceptions\Response\ServiceException;
use App\Mappers\System\Permission\TreeMapper;
use App\Models\Admin\Auth\Permission\Tree;

class TreeService
{
    use ServiceTrait;

    /**
     * 获取数据列表
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Collection
     */
    public function getList($request)
    {
        $trees = collect();
        $user = $request->user()->load('treeRoles');

        // 循环获取所有权限树
        $user->treeRoles->map(function ($tree) {
            return Tree::selectAllById($tree->id)->toArray();
        })
        // 合并授权树
        ->each(function ($items, $key) use ($trees) {
            $trees->push(...$items);
        });

        return $trees->unique('id')->sortBy('parent_id')->sortBy('sort');
    }

    /**
     * 创建数据
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\Admin\Auth\Permission\Tree
     */
    public function store($request)
    {
        // 事物回滚
        return Tree::dbConnection()->transaction(function () use ($request) {
            // 创建节点
            $tree = Tree::create(
                TreeMapper::make($request)->toArray()
            );
            // 父级节点
            $parent = Tree::firstOrNew(['id' => $tree->parent_id], ['path' => '0']);
            // 更新关联字段
            $tree->sort = $tree->id;
            $tree->path = $parent->path . '.' . $tree->id;
            $tree->save();

            // 分级授权树创建完成
            \App\Events\System\Permission\TreeCreated::dispatch($tree);

            return $tree;
        });
    }

    /**
     * 更新数据
     *
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\Admin\Auth\Permission\Tree
     */
    public function update($id, $request)
    {
        // 权限树
        $tree = Tree::findOrFail($id);
        // 修改数据
        $tree->fill(
            TreeMapper::make($request)->toArray()
        );
        // 保存数据
        $tree->save();

        return $tree;
    }

    /**
     * 删除数据
     *
     * @param int $id
     * @throws ServiceException
     * @return \App\Models\Admin\Auth\Permission\Tree
     */
    public function destroy($id)
    {
        // 有无子节点
        if (Tree::where('parent_id', $id)->exists()) {
            throw new ServiceException(ServiceException::SERVICE_EXISTED_ORG_CHILDREN);
        }
        // 权限树
        $tree = Tree::findOrFail($id);

        // 事物回滚
        Tree::dbConnection()->transaction(function () use ($tree) {
            // 分级授权树删除前
            \App\Events\System\Permission\TreeDeleting::dispatch($tree);
            
            // 删除节点
            $tree->delete();
        });

        return $tree;
    }
}
