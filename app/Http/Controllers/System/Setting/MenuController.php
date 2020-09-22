<?php
namespace App\Http\Controllers\System\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\System\Setting\Menu\StoreRequest;
use App\Http\Requests\System\Setting\Menu\UpdateRequest;
use App\Http\Resources\Common\TreeResource;
use App\Http\Resources\Common\DataResource;
use App\Services\System\Setting\MenuService;

class MenuController extends Controller
{

    /**
     * 获取数据列表
     */
    public function getList(Request $request)
    {
        $collection = MenuService::create()->getList($request);
        
        return new TreeResource($collection);
    }

    /**
     * 创建数据
     */
    public function store(StoreRequest $request)
    {
        $menu = MenuService::create()->store($request);
        
        return new DataResource($menu);
    }

    /**
     * 更新数据
     *
     * @param int $id            
     */
    public function update(UpdateRequest $request)
    {
        $id = $request->input('id');
        
        $menu = MenuService::create()->update($id, $request);
        
        return new DataResource($menu);
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
        
        $menu = MenuService::create()->destroy($id);
        
        return new DataResource($menu);
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
        
        $menu = MenuService::create()->restore($id);
        
        return new DataResource($menu);
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
        
        $menu = MenuService::create()->forceDestroy($id);
        
        return new DataResource($menu);
    }
}
