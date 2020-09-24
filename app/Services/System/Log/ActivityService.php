<?php
namespace App\Services\System\Log;

use App\Services\Service;
use App\Models\Admin\Spatie\Activitylog\Activity;
use Illuminate\Support\Str;

class ActivityService extends Service
{

    /**
     * 获取数据列表
     *
     * @param \Illuminate\Http\Request $request            
     * @return \Illuminate\Database\Eloquent\Collection[]|int[]
     */
    public function getList($request)
    {
        $query = Activity::query();
        
        $keyword = $request->input('keyword');
        // 搜索
        if ($keyword === 'null') {
            // 操作人【null】
            $query->whereNull('causer_id');
        } elseif (ctype_digit($keyword)) {
            // 操作人【ID】
            $this->makeWhereParam($request, 'keyword', $query, 'causer_id');
        } elseif (Str::of($keyword)->is('*:*')) {
            // 拆分字符串
            $subject = Str::of($keyword)->explode(':');
            // 操作对象
            $query->where('subject_id', $subject->last());
            $query->where('subject_type', $subject->first());
        } elseif ($this->isValidParam($request, 'keyword')) {
            // 格式错误筛选填充
            $query->where('id', 0);
        }
        
        // 事件
        $this->makeWhereParam($request, 'description', $query, 'description');
        
        // 数据条数
        $total = (clone $query)->count();
        // 查询数据
        $collection = $query->with('causer:id,name,email')
            ->orderBy('created_at', $request->get('order', Activity::ORDER_DESC))
            ->offset(($this->getPage($request) - 1) * $this->getPageSize($request))
            ->limit($this->getPageSize($request))
            ->get();
        
        return [
            $collection,
            $total
        ];
    }
}