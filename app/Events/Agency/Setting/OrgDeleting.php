<?php
namespace App\Events\Agency\Setting;

use Illuminate\Foundation\Events\Dispatchable;

class OrgDeleting
{
    use Dispatchable;

    /**
     * 代理平台
     *
     * @var \App\Models\Agency\Agency\Org
     */
    public $org;

    /**
     * 代理平台删除前
     *
     * @param \App\Models\Agency\Agency\Org $org            
     * @return void
     */
    public function __construct($org)
    {
        $this->org = $org;
    }
}
