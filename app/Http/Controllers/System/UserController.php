<?php
namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\System\User\ItemRequest;
use App\Http\Resources\System\User\DatasResource;
use App\Http\Resources\Common\DataResource;
use App\Models\Admin\Auth\User;
use App\Services\System\UserService;

class UserController extends Controller
{

    /**
     * 获取数据列表
     */
    public function getList(Request $request)
    {
        $exceptSuperAdmin = ! $request->user()->isSuperAdmin();
        
        list ($collection, $total) = UserService::create()->getList($request, $exceptSuperAdmin);
        
        return DatasResource::make($collection)->setPaginateTotal($total);
    }

    /**
     * 保存数据
     *
     * @param int $id            
     */
    public function saveItem(ItemRequest $request)
    {
        $id = $request->input('id', null);
        
        $user = UserService::create()->saveItem($request, $id);
        
        return new DataResource($user);
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
        
        $user = User::findOrFail($id);
        $user->delete();
        
        return new DataResource($user);
    }

    /**
     * 恢复删除
     *
     * @param int $id            
     */
    public function restore(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer|min:1'
        ]);
        $id = $request->input('id');
        
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        
        return new DataResource($user);
    }

    /**
     * 彻底删除
     *
     * @param int $id            
     */
    public function forceDestroy(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer|min:1'
        ]);
        $id = $request->input('id');
        
        $user = User::withTrashed()->findOrFail($id);
        $user->forceDelete();
        
        return new DataResource($user);
    }
}
