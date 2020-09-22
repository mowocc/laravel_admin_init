<?php
namespace App\Services\Auth;

use App\Services\ServiceTrait;
use App\Models\Admin\Auth\UserConfig;
use App\Models\Admin\Config\Language;
use App\Models\Admin\Config\Server;

class UserConfigService
{
    use ServiceTrait;

    /**
     * 个人语言设置
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user        
     * @param string $lang            
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @return \App\Models\Admin\Auth\UserConfig
     */
    public function setLanguage($user, $lang)
    {
        $language = Language::where('lang', $lang)->firstOrFail();
        
        $config = UserConfig::firstOrNew(['user_id' => $user->id]);
        
        $config->fill(['language_lang' => $language->lang])->save();
        
        return $config;
    }
    
    /**
     * 个人服务器设置
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param int $serverId
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @return \App\Models\Admin\Auth\UserConfig|NULL
     */
    public function setServer($user, $serverId)
    {
        $server = Server::findOrFail($serverId);
        
        $config = UserConfig::firstOrNew(['user_id' => $user->id]);
        
        $config->fill(['server_id' => $server->id])->save();
        
        return $config;
    }
}
