<?php
namespace App\Services\Agency\Setting;

use App\Services\ServiceTrait;
use App\Exceptions\Response\ServiceException;
use App\Mappers\Agency\Setting\OrgMapper;
use App\Models\Agency\Agency\Org;

class OrgService
{
    use ServiceTrait;
    
    /**
     * 创建数据
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\Agency\Agency\Org
     */
    public function store($request)
    {
        // 事物回滚
        return Org::dbConnection()->transaction(function () use ($request) {
            // 创建节点
            $org = Org::create(
                OrgMapper::make($request)->toArray()
            );
            // 父级节点
            $parent = Org::firstOrNew(['id' => $org->parent_id], ['path' => '0']);
            // 更新关联字段
            $org->sort = $org->id;
            $org->path = $parent->path . '.' . $org->id;
            $org->save();

            return $org;
        });
    }

    /**
     * 更新数据
     *
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\Agency\Agency\Org
     */
    public function update($id, $request)
    {
        // 权限树
        $org = Org::findOrFail($id);
        // 修改数据
        $org->fill(
            OrgMapper::make($request)->toArray()
        );
        // 保存数据
        $org->save();

        return $org;
    }

    /**
     * 删除数据
     *
     * @param int $id
     * @throws ServiceException
     * @return \App\Models\Agency\Agency\Org
     */
    public function destroy($id)
    {
        // 有无子节点
        if (Org::where('parent_id', $id)->exists()) {
            throw new ServiceException(ServiceException::SERVICE_EXISTED_ORG_CHILDREN);
        }
        // 权限树
        $org = Org::findOrFail($id);

        // 事物回滚
        Org::dbConnection()->transaction(function () use ($org) {
            // 代理平台删除前
            \App\Events\Agency\Setting\OrgDeleting::dispatch($org);
            
            // 删除节点
            $org->delete();
        });

        return $org;
    }
}
