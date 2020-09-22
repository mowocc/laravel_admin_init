<?php
namespace App\Http\Controllers\System\Log;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\System\Log\Activity\DatasResource;
use App\Services\System\Log\ActivityService;

class ActivityController extends Controller
{

    /**
     * 获取数据列表
     */
    public function getList(Request $request)
    {
        list ($collection, $total) = ActivityService::create()->getList($request);
        
        return DatasResource::make($collection)->setPaginateTotal($total);
    }
}
