<?php
namespace App\Services\Auth;

use App\Services\ServiceTrait;
use Jenssegers\Agent\Agent;
use App\Models\Admin\Auth\UserLoginLog;

class UserLoginLogService
{
    use ServiceTrait;

    /**
     * 制作 Geoip 数据
     *
     * @return array
     */
    protected function makeGeoipData()
    {
        $data = geoip()->getLocation()->toArray();
        
        return collect($data)->only('ip', 'iso_code', 'country', 'city', 'state', 'state_name', 'postal_code', 'lat', 'lon', 'timezone', 'continent', 'currency', 'default')->toArray();
    }

    /**
     * 制作 Agent 数据
     *
     * @return array
     */
    protected function makeAgentData()
    {
        $agent = new Agent();
        
        return collect([
            'languages' => $agent->languages(),
            'device' => $agent->device(),
            'platform' => $agent->platform(),
            'platform_version' => $agent->version($agent->platform()),
            'browser' => $agent->browser(),
            'browser_version' => $agent->version($agent->browser()),
            'robot' => $agent->robot()
        ])
        // 删除空数据
        ->reject(function ($item) {
            return empty($item);
        })
        ->toArray();
    }

    /**
     * 创建数据
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user            
     * @return \App\Models\Admin\Auth\UserLoginLog
     */
    public function store($user)
    {
        return UserLoginLog::create(
            ['user_id' => $user->id] + 
            $this->makeGeoipData() + 
            $this->makeAgentData()
        );
    }
}
