<?php
namespace App\Services\Agency\Contacts;

use App\Services\Service;
use App\Exceptions\Response\ServiceException;
use App\Mappers\Agency\Contacts\UserMapper;
use App\Models\Agency\Auth\User;

class UserService extends Service
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
        $query = User::query();
        
        // 软删除筛选
        if (! $this->isValidParam($request, 'deleted')) {
            $query->withTrashed();
        } elseif ((boolean) $request->input('deleted')) {
            $query->onlyTrashed();
        }
        
        // 制作筛选参数
        $this->makeWhereParam($request, 'org_id', $where, 'org_id')
            ->makeWhereParamLike($request, 'keyword', $where, 'email');
         
        // 数据条数
        $total = (clone $query)->where($where)->count();
        // 查询数据
        $collection = $query->with(['org:id,name', 'treeRoles:id,name'])
            ->where($where)
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
     * @return \App\Models\Agency\Auth\User
     */
    public function saveItem($request, $id = null)
    {
        // 验证邮箱是否已存在
        $this->verifyUserExistedByEmail($request->input('email'), $id);

        // 数据映射
        $data = UserMapper::make($request)->toArray();

        // 用户模型
        $user = User::withTrashed()->findOrNew($id);
        // 保存数据
        $user->fill($data)->save();
        
        // 超级管理员
        if ((boolean) $request->input('is_super_admin')) {
            $user->assignSuperAdmin();
        } else {
            $user->removeSuperAdmin();
        }

        return $user;
    }
    
    /**
     * 验证邮箱已存在（已存在则抛出异常）
     *
     * @param string $email
     * @param int $selfId
     * @throws ServiceException
     * @return $this
     */
    public function verifyUserExistedByEmail($email, $selfId = null)
    {
        $user = User::withTrashed()->where('email', $email)->first();
        
        if (! $selfId && $user) {
            throw new ServiceException(ServiceException::SERVICE_EXISTED_EMAIL);
        } elseif ($selfId && $user && $selfId != $user->id) {
            throw new ServiceException(ServiceException::SERVICE_EXISTED_EMAIL);
        }
        return $this;
    }
}
