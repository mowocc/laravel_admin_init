<?php
namespace App\Services\System;

use App\Services\Service;
use App\Services\Auth\UserService as AuthUserService;
use App\Mappers\System\UserMapper;
use App\Models\Admin\Auth\User;

class UserService extends Service
{

    /**
     * 获取数据列表
     *
     * @param \Illuminate\Http\Request $request
     * @param boolean $exceptSuperAdmin
     * @return \Illuminate\Database\Eloquent\Collection[]|int[]
     */
    public function getList($request, $exceptSuperAdmin = true)
    {
        $query = User::query();
        
        // 排除超级管理员
        if ((boolean) $exceptSuperAdmin) {
            $query->where('is_super_admin', User::$superAdminFalse);
        }
        
        // 软删除筛选
        $this->makeWhereDeleted($request, $query);
        // 邮箱左搜索
        $this->makeWhereParamLike($request, 'keyword', $query, 'email');
            
        // 数据条数
        $total = (clone $query)->count();
        // 查询数据
        $collection = $query->with(['treeRoles:id,name'])
            ->orderBy($request->get('order_column', 'id'), $request->get('order', User::ORDER_DESC))
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
     * @return \App\Models\Admin\Auth\User
     */
    public function saveItem($request, $id = null)
    {
        // 验证邮箱是否已存在
        AuthUserService::create()->verifyUserExistedByEmail($request->input('email'), $id);

        // 数据映射
        $data = UserMapper::make($request)->toArray();

        // 用户模型
        $user = User::withTrashed()->findOrNew($id);
        // 保存数据
        $user->fill($data)->save();
        
        // 谁在编辑【非超级管理员】
        if (! $request->user()->isSuperAdmin()) {
            return $user;
        }
        
        // 超级管理员
        if ((boolean) $request->input('is_super_admin')) {
            $user->assignSuperAdmin();
        } else {
            $user->removeSuperAdmin();
        }
        
        return $user;
    }
}
