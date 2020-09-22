<?php
namespace App\Listeners\Agency\Setting;

use App\Events\Agency\Setting\OrgDeleting;
use App\Models\Agency\Auth\User;

class AgencyUserRemove
{

    /**
     * 删除代理后台用户
     *
     * @param OrgDeleting $event            
     * @return void
     */
    public function handle(OrgDeleting $event)
    {
        User::where('org_id', $event->org->id)->delete();
    }
}
