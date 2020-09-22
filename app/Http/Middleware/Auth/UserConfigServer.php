<?php

namespace App\Http\Middleware\Auth;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Services\Auth\UserConfigService;
use App\Models\Admin\Config\Server;

/**
 * 用户应用Server配置
 */
class UserConfigServer
{

    /**
     * 前置 | 后置
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 存在用户Server配置
        if (isset($request->user()->config->server)) {
            // 默认配置
            $connectionDefault = config('database.connections.mysql_agency');
            // 用户Server配置
            $connectionUserConfig = $request->user()->config->server->mysql_agency;

            // 更新当前请求的连接配置
            config(['database.connections.mysql_agency' => $connectionUserConfig + $connectionDefault]);

            return $next($request);
        }

        // 存在服务器配置列表
        if (!is_null($server = Server::first())) {
            // 为用户设置默认服务器
            UserConfigService::create()->setServer($request->user(), $server->id);

            // 默认配置
            $connectionDefault = config('database.connections.mysql_agency');
            // 动态属性已经加载，更新后需重新加载
            $connectionUserConfig = $request->user()->config()->first()->server()->first()->mysql_agency;

            // 更新当前请求的连接配置
            config(['database.connections.mysql_agency' => $connectionUserConfig + $connectionDefault]);
        }

        return $next($request);
    }
}
