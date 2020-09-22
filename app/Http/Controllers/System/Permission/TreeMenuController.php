<?php
namespace App\Http\Controllers\System\Permission;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\System\Permission\TreeMenu\MenusResource;
use App\Http\Resources\System\Permission\TreeMenu\MenuListResource;
use App\Services\System\Permission\TreeMenuService;
use App\Services\System\Permission\TreeMenuActionService;

class TreeMenuController extends Controller
{

    /**
     * 获取页面列表
     */
    public function getMenuList(Request $request)
    {
        $this->validate($request, [
            'tree_id' => 'required|integer|min:0'
        ]);
        $treeId = $request->get('tree_id');
        
        $menus = TreeMenuService::create()->getMenus($treeId);
        $menuActions = TreeMenuActionService::create()->getMenuActions($treeId);
        
        return MenuListResource::make($menus)->setMenuAction($menuActions);
    }

    /**
     * 获取授权页面
     */
    public function getMenus(Request $request)
    {
        $this->validate($request, [
            'tree_id' => 'required|integer|min:1'
        ]);
        $treeId = $request->get('tree_id');
        
        $menus = TreeMenuService::create()->getMenus($treeId);
        $menuActions = TreeMenuActionService::create()->getMenuActions($treeId);
        
        return MenusResource::make($menus)->setMenuAction($menuActions);
    }

    /**
     * 更新授权页面
     *
     * @param int $treeId            
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'tree_id' => 'required|integer|min:1'
        ]);
        $treeId = $request->input('tree_id');
        
        $menus = TreeMenuService::create()->update($treeId, $request);
        $menuActions = TreeMenuActionService::create()->update($treeId, $request);
        
        return MenusResource::make($menus)->setMenuAction($menuActions);
    }
}
