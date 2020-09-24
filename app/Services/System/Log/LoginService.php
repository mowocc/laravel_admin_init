<?php
namespace App\Services\System\Log;

use App\Services\Service;
use App\Models\Admin\Auth\UserLoginLog;

class LoginService extends Service
{

    /**
     * 获取数据列表
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Collection[]|int[]
     */
    public function getList($request)
    {
        $query = UserLoginLog::query();

        // 搜索
        if (ctype_digit($request->input('keyword'))) {
            // 操作人【ID】
            $this->makeWhereParam($request, 'keyword', $query, 'user_id');
        }
        // 默认值
        $this->makeWhereParam($request, 'default', $query, 'default');
        
        // 数据条数
        $total = (clone $query)->count();
        // 查询数据
        $collection = $query->with('user:id,name,email')
            ->orderBy('created', $request->get('order', UserLoginLog::ORDER_DESC))
            ->offset(($this->getPage($request) - 1) * $this->getPageSize($request))
            ->limit($this->getPageSize($request))
            ->get();

        return [
            $collection,
            $total
        ];
    }
}
