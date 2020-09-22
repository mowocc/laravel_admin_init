<?php
use Illuminate\Support\Facades\Route;

/*
 * |--------------------------------------------------------------------------
 * | API Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register API routes for your application. These
 * | routes are loaded by the RouteServiceProvider within a group which
 * | is assigned the "api" middleware group. Enjoy building your API!
 * |
 */

Route::group([
    'middleware' => ['auth:sanctum', 'auth.user.server']
], function () {
    // 用户认证
    Route::group([
        'prefix' => 'auth',
        'namespace' => 'Auth'
    ], function () {
        Route::post('logout', 'LoginController@logout');
    });

    // Admin
    Route::group([
        'prefix' => 'admin'
    ], function () {
        Route::get('getMenus', 'AdminController@getMenus');
        Route::get('getServers', 'AdminController@getServers');
        Route::post('setServer', 'AdminController@setServer');
    });

    // Home
    Route::group([
        'prefix' => 'home'
    ], function () {
        Route::get('getUser', 'HomeController@getUser');
        Route::post('update', 'HomeController@update');
        Route::post('updatePassword', 'HomeController@updatePassword');
        Route::post('setLanguage', 'HomeController@setLanguage');
    });

    // Common
    Route::group([
        'prefix' => 'common'
    ], function () {
        Route::get('getOrgTree', 'CommonController@getOrgTree');
    });

    // 代理
    Route::group([
        'prefix' => 'agency',
        'namespace' => 'Agency'
    ], function () {
        // 代理管理
        Route::group([
            'prefix' => 'setting',
            'namespace' => 'Setting',
            'middleware' => 'can:menu.agency.setting'
        ], function () {
            // 代理平台
            Route::group([
                'prefix' => 'org'
            ], function () {
                Route::get('getList', 'OrgController@getList');
                Route::post('store', 'OrgController@store');
                Route::post('update', 'OrgController@update');
                Route::post('destroy', 'OrgController@destroy');
            });
        });
        // 成员管理
        Route::group([
            'prefix' => 'contacts',
            'namespace' => 'Contacts',
            'middleware' => 'can:menu.agency.contacts'
        ], function () {
            // 代理用户
            Route::group([
                'prefix' => 'user'
            ], function () {
                Route::get('getList', 'UserController@getList');
                Route::post('saveItem', 'UserController@saveItem');
                Route::post('destroy', 'UserController@destroy');
                Route::post('restore', 'UserController@restore');
                Route::post('forceDestroy', 'UserController@forceDestroy');
            });
        });
    });

    // 系统
    Route::group([
        'prefix' => 'system',
        'namespace' => 'System'
    ], function () {
        // 系统配置
        Route::group([
            'prefix' => 'setting',
            'namespace' => 'Setting'
        ], function () {
            // 语言配置
            Route::group([
                'prefix' => 'language',
                'middleware' => 'can:menu.system.setting.language'
            ], function () {
                Route::get('getList', 'LanguageController@getList');
                Route::post('saveItem', 'LanguageController@saveItem');
                Route::post('destroy', 'LanguageController@destroy');
                Route::post('restore', 'LanguageController@restore');
                Route::post('forceDestroy', 'LanguageController@forceDestroy');
            });
            // 分区配置
            Route::group([
                'prefix' => 'server',
                'middleware' => 'can:menu.system.setting.server'
            ], function () {
                Route::get('getList', 'ServerController@getList');
                Route::post('saveItem', 'ServerController@saveItem');
                Route::post('destroy', 'ServerController@destroy');
                Route::post('restore', 'ServerController@restore');
                Route::post('forceDestroy', 'ServerController@forceDestroy');
            });
            // 目录菜单
            Route::group([
                'prefix' => 'menu',
                'middleware' => 'can:menu.system.setting.menu'
            ], function () {
                Route::get('getList', 'MenuController@getList');
                Route::post('store', 'MenuController@store');
                Route::post('update', 'MenuController@update');
                Route::post('destroy', 'MenuController@destroy');
                Route::post('restore', 'MenuController@restore');
                Route::post('forceDestroy', 'MenuController@forceDestroy');
                // 页面功能
                Route::group([
                    'prefix' => 'action'
                ], function () {
                    Route::get('getList', 'MenuActionController@getList');
                    Route::post('save', 'MenuActionController@save');
                });
            });
        });
        // 后台用户
        Route::group([
            'prefix' => 'user',
            'middleware' => 'can:menu.system.user'
        ], function () {
            Route::get('getList', 'UserController@getList');
            Route::post('saveItem', 'UserController@saveItem');
            Route::post('destroy', 'UserController@destroy');
            Route::post('restore', 'UserController@restore');
            Route::post('forceDestroy', 'UserController@forceDestroy');
        });
        // 权限管理
        Route::group([
            'prefix' => 'permission',
            'namespace' => 'Permission',
            'middleware' => 'can:menu.system.permission'
        ], function () {
            // 权限树
            Route::group([
                'prefix' => 'tree'
            ], function () {
                Route::get('getList', 'TreeController@getList');
                Route::post('store', 'TreeController@store');
                Route::post('update', 'TreeController@update');
                Route::post('destroy', 'TreeController@destroy');
            });
            // 授权用户
            Route::group([
                'prefix' => 'tree-user'
            ], function () {
                Route::get('getUserList', 'TreeUserController@getUserList');
                Route::get('getUsers', 'TreeUserController@getUsers');
                Route::post('update', 'TreeUserController@update');
            });
            // 授权页面
            Route::group([
                'prefix' => 'tree-menu'
            ], function () {
                Route::get('getMenuList', 'TreeMenuController@getMenuList');
                Route::get('getMenus', 'TreeMenuController@getMenus');
                Route::post('update', 'TreeMenuController@update');
            });
        });
        // 活动日志
        Route::group([
            'prefix' => 'log',
            'namespace' => 'Log'
        ], function () {
            // 操作日志
            Route::group([
                'prefix' => 'activity',
                'middleware' => 'can:menu.system.log.activity'
            ], function () {
                Route::get('getList', 'ActivityController@getList');
            });
            // 登录日志
            Route::group([
                'prefix' => 'login',
                'middleware' => 'can:menu.system.log.login'
            ], function () {
                Route::get('getList', 'LoginController@getList');
            });
        });
    });
});
