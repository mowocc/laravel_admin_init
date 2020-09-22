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
        $where = [];
        $query = UserLoginLog::query();

        // 搜索
        if (ctype_digit($request->input('keyword'))) {
            // 操作人【ID】
            $this->makeWhereParam($request, 'keyword', $where, 'user_id');
        }
        // 默认值
        $this->makeWhereParam($request, 'default', $where, 'default');
        
        // 数据条数
        $total = (clone $query)->where($where)->count();
        // 查询数据
        $collection = $query->with('user:id,name,email')
            ->where($where)
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
