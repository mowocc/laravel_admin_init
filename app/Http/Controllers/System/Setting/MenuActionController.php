<?php
namespace App\Http\Controllers\System\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\System\Setting\MenuAction\ItemsRequest;
use App\Http\Resources\System\Setting\MenuAction\DatasResource;
use App\Services\System\Setting\MenuActionService;

class MenuActionController extends Controller
{

    /**
     * 获取数据列表
     */
    public function getList(Request $request)
    {
        $this->validate($request, [
            'menu_id' => 'required|integer|min:1'
        ]);
        $menuId = $request->input('menu_id');
        
        $collection = MenuActionService::create()->getList($menuId);
        
        return new DatasResource($collection);
    }

    /**
     * 创建数据
     */
    public function save(ItemsRequest $request)
    {
        $menuId = $request->input('menu_id');
        
        $collection = MenuActionService::create()->save($menuId, $request);
        
        return new DatasResource($collection);
    }
}
