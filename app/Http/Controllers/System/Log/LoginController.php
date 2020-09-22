<?php
namespace App\Http\Controllers\System\Log;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Common\DatasResource;
use App\Services\System\Log\LoginService;

class LoginController extends Controller
{

    /**
     * 获取数据列表
     */
    public function getList(Request $request)
    {
        list ($collection, $total) = LoginService::create()->getList($request);
        
        return DatasResource::make($collection)->setPaginateTotal($total);
    }
}
