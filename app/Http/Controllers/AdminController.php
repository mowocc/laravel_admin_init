<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Common\DataResource;
use App\Http\Resources\Admin\MenuResource;
use App\Models\Admin\Config\MenuAction;
use App\Models\Admin\Config\Menu;
use App\Models\Admin\Config\Server;
use App\Services\Auth\UserConfigService;

class AdminController extends Controller
{

    /**
     * 管理后台
     */
    public function index(Request $request)
    {
        if ($request->user()->isSuperAdmin()) {
            return view('admin');
        }

        // 验证授权页面
        if (! $request->user()->isTreePermissionMenus()) {
            // 用户退出登录
            Auth::logout();
            // 提示没有权限
            abort(403);
        }

        return view('admin');
    }

    /**
     * 获取目录菜单
     *
     * @param Request $request
     */
    public function getMenus(Request $request)
    {
        if ($request->user()->isSuperAdmin()) {
            // 获取授权页面【全部】
            $menus = Menu::selectAll();
            // 获取授权页面功能【全部】
            $menuActions = MenuAction::all();
        } else {
            // 获取授权页面
            $menus = $request->user()->getTreePermissionMenus();
            // 获取授权页面功能
            $menuActions = $request->user()->getTreePermissionMenuActions();
        }

        return MenuResource::make($menus)->setMenuAction($menuActions);
    }

    /**
     * 获取服务器分区
     *
     * @param Request $request
     */
    public function getServers(Request $request)
    {
        // 中间件[auth.user.server]可能会更新用户server配置，需重强制重新加载
        return new DataResource([
            'server' => $request->user()->config()->first()->server()->first() ?? [],
            'servers' => Server::all()
        ]);
    }

    /**
     * 设置用户当前服务器
     *
     * @param \Illuminate\Http\Request $request
     */
    public function setServer(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer|min:1'
        ]);
        $id = $request->post('id');

        $config = UserConfigService::create()->setServer($request->user(), $id);

        return new DataResource($config->server);
    }
}
