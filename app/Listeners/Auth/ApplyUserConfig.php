<?php
namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Cookie;
use App\Services\Auth\UserConfigService;

class ApplyUserConfig
{

    /**
     * 应用个人语言设置
     *
     * @param Login $event            
     * @return void
     */
    public function handle(Login $event)
    {
        // 当前语言设置
        if (isset($event->user->config->language)) {
            Cookie::queue('lang', $event->user->config->language->lang,  now()->diffInMinutes(now()->addYear()), null, null, null, false);
        } 
        
        // 个人语言设置
        elseif (! is_null($lang = Cookie::get('lang'))) {
            try {
                UserConfigService::create()->setLanguage($event->user, $lang);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {}
        }
        
        // 个人默认语言
        else {
            try {
                UserConfigService::create()->setLanguage($event->user, config('app.locale'));
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {}
        }
    }
}
