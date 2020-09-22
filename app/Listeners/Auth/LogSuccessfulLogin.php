<?php
namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Login;
use App\Services\Auth\UserLoginLogService;

class LogSuccessfulLogin
{

    /**
     * 登录成功日志
     *
     * @param Login $event
     * @return void
     */
    public function handle(Login $event)
    {
        UserLoginLogService::create()->store($event->user);
    }
}
