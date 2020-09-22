<?php
namespace App\Http\Controllers\System\Permission;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\System\Permission\Tree\StoreRequest;
use App\Http\Requests\System\Permission\Tree\UpdateRequest;
use App\Http\Resources\Common\TreeResource;
use App\Http\Resources\Common\DataResource;
use App\Models\Admin\Auth\Permission\Tree;
use App\Services\System\Permission\TreeService;

class TreeController extends Controller
{

    /**
     * 获取数据列表
     */
    public function getList(Request $request)
    {
        if ($request->user()->isSuperAdmin()) {
            // 获取分级授权数据【全部】
            $trees = Tree::selectAll();
        } else {
            // 获取分级授权数据
            $trees = TreeService::create()->getList($request);
        }

        return new DataResource([
            'dataList' => $trees->keyBy('id')->toArray(),
            'treeList' => TreeResource::make($trees)->toTree($request),
        ]);
    }

    /**
     * 创建数据
     */
    public function store(StoreRequest $request)
    {
        // 只有超级管理员才能创建根节点
        if (!$request->input('parent_id') && !$request->user()->isSuperAdmin()) {
            // 提示没有权限
            abort(403);
        }

        $tree = TreeService::create()->store($request);

        return new DataResource($tree);
    }

    /**
     * 更新数据
     *
     * @param int $id
     */
    public function update(UpdateRequest $request)
    {
        $id = $request->input('id');

        $tree = TreeService::create()->update($id, $request);

        return new DataResource($tree);
    }

    /**
     * 删除数据
     *
     * @param int $id
     */
    public function destroy(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer|min:1'
        ]);
        $id = $request->input('id');

        $tree = TreeService::create()->destroy($id);

        return new DataResource($tree);
    }
}
