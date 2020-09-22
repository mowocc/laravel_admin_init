<?php
namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Events\StatementPrepared;
use App\Events\System\Permission\TreeCreated;

class EventServiceProvider extends ServiceProvider
{

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class
        ],
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\Auth\ApplyUserConfig',
            'App\Listeners\Auth\LogSuccessfulLogin',
        ],
        // 代理平台删除前
        'App\Events\Agency\Setting\OrgDeleting' => [
            // 删除代理后台用户
            'App\Listeners\Agency\Setting\AgencyUserRemove',
        ],
        // 分级授权树创建完成
        'App\Events\System\Permission\TreeCreated' => [
            // 创建角色(授权树)
            'App\Listeners\System\Permission\TreeRoleCreate'
        ],
        // 分级授权树删除前
        'App\Events\System\Permission\TreeDeleting' => [
            // 删除角色(授权树)
            'App\Listeners\System\Permission\TreeRoleRemove',
        ],
        // 授权用户更新完成
        'App\Events\System\Permission\TreeUser\Updated' => [
            // 角色(授权树)同步授权用户
            'App\Listeners\System\Permission\TreeUser\TreeRoleSyncUsers'
        ],
        // 授权页面更新完成
        'App\Events\System\Permission\TreeMenu\Updated' => [
            // 角色(授权树)同步授权页面
            'App\Listeners\System\Permission\TreeMenu\TreeRoleSyncMenus',
            // 角色(授权树)递归回收页面权限
            'App\Listeners\System\Permission\TreeMenu\TreeRolesRevokeMenuPermissions'
        ],
        // 授权页面功能更新完成
        'App\Events\System\Permission\TreeMenuAction\Updated' => [
            // 角色(授权树)同步授权页面功能
            'App\Listeners\System\Permission\TreeMenuAction\TreeRoleSyncMenuActions',
            // 角色(授权树)递归回收页面功能权限
            'App\Listeners\System\Permission\TreeMenuAction\TreeRolesRevokeMenuActionPermissions'
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // 修改 PDO Fetch Mode
        Event::listen(StatementPrepared::class, function ($event) {
            $event->statement->setFetchMode(\PDO::FETCH_ASSOC);
        });
    }
}
