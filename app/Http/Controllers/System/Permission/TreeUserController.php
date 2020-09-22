<?php
namespace App\Http\Controllers\System\Permission;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Common\DataResource;
use App\Http\Resources\Common\DatasResource;
use App\Services\System\Permission\TreeUserService;
use App\Services\System\UserService;

class TreeUserController extends Controller
{

    /**
     * 获取用户列表
     */
    public function getUserList(Request $request)
    {
        $request->offsetSet('deleted', false);

        list ($collection, $total) = UserService::create()->getList($request);

        return DatasResource::make($collection)->setPaginateTotal($total);
    }

    /**
     * 获取授权用户
     */
    public function getUsers(Request $request)
    {
        $this->validate($request, [
            'tree_id' => 'required|integer|min:1'
        ]);
        $treeId = $request->get('tree_id');

        $users = TreeUserService::create()->getUsers($treeId);

        return new DataResource($users);
    }

    /**
     * 更新授权用户
     *
     * @param int $treeId
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'tree_id' => 'required|integer|min:1'
        ]);
        $treeId = $request->input('tree_id');

        $users = TreeUserService::create()->update($treeId, $request);

        return new DataResource($users);
    }
}
