<?php
namespace App\Services\System\Setting;

use App\Services\Service;
use App\Mappers\System\Setting\ServerMapper;
use App\Models\Admin\Config\Server;

class ServerService extends Service
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
        $query = Server::query();

        // 软删除筛选
        if (! $this->isValidParam($request, 'deleted')) {
            $query->withTrashed();
        } elseif ((boolean) $request->input('deleted')) {
            $query->onlyTrashed();
        }

        // 制作筛选参数
        $this->makeWhereParamLike($request, 'keyword', $where, 'name');

        // 数据条数
        $total = (clone $query)->where($where)->count();
        // 查询数据
        $collection = $query->where($where)
            ->orderBy($request->get('order_column', 'id'), $request->get('order', Server::ORDER_DESC))
            ->offset(($this->getPage($request) - 1) * $this->getPageSize($request))
            ->limit($this->getPageSize($request))
            ->get();

        return [
            $collection->makeVisible('mysql_agency'),
            $total
        ];
    }

    /**
     * 保存数据
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \App\Models\Admin\Auth\Server
     */
    public function saveItem($request, $id = null)
    {
        // 数据映射
        $data = ServerMapper::make($request)->toArray();

        // 用户模型
        $server = Server::withTrashed()->findOrNew($id);
        // 保存数据
        $server->fill($data)->save();

        return $server->makeVisible('mysql_agency');
    }
}
