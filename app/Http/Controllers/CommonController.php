<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Common\TreeResource;
use App\Models\Agency\Agency\Org;

class CommonController extends Controller
{

    /**
     * 获取代理平台
     */
    public function getOrgTree(Request $request)
    {
        return new TreeResource(Org::selectAll());
    }
}
