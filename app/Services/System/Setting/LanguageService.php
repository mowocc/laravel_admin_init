<?php
namespace App\Services\System\Setting;

use App\Services\Service;
use App\Mappers\System\Setting\LanguageMapper;
use App\Models\Admin\Config\Language;

class LanguageService extends Service
{

    /**
     * 获取数据列表
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Collection[]|int[]
     */
    public function getList($request)
    {
        $query = Language::query();

        // 软删除筛选
        $this->makeWhereDeleted($request, $query);
        // 语言左搜索
        $this->makeWhereParamLike($request, 'keyword', $query, 'name');

        // 数据条数
        $total = (clone $query)->count();
        // 查询数据
        $collection = $query
            ->orderBy($request->get('order_column', 'id'), $request->get('order', Language::ORDER_DESC))
            ->offset(($this->getPage($request) - 1) * $this->getPageSize($request))
            ->limit($this->getPageSize($request))
            ->get();

        return [
            $collection,
            $total
        ];
    }

    /**
     * 保存数据
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \App\Models\Admin\Auth\Language
     */
    public function saveItem($request, $id = null)
    {
        // 数据映射
        $data = LanguageMapper::make($request)->toArray();

        // 用户模型
        $language = Language::withTrashed()->findOrNew($id);
        // 保存数据
        $language->fill($data)->save();

        return $language;
    }
}
