<?php
namespace App\Models\Admin\Auth\Permission;

use App\Models\Admin\Model;
use App\Models\Admin\Auth\User;
use App\Models\Admin\Config\Menu;
use App\Models\Admin\Config\MenuAction;
use App\Models\Traits\Admin\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tree extends Model
{
    use LogsActivity;

    /**
     * 数据表名称
     *
     * @var string
     */
    protected $table = 'auth_permission_trees';

    /**
     * 查询后隐藏的字段
     *
     * @var array
     */
    protected $hidden = [
        'created',
        'updated'
    ];

    /**
     * 可以批量赋值的字段
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'path',
        'name',
        'sort'
    ];

    /**
     * 拥有此角色的所有用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class, TreeUser::tableName(), 'tree_id', 'user_id');
    }
    
    /**
     * 拥有此角色的所有页面
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function menus() : BelongsToMany
    {
        return $this->belongsToMany(Menu::class, TreeMenu::tableName(), 'tree_id', 'menu_route_name', 'id', 'route_name');
    }
    
    /**
     * 拥有此角色的所有页面功能
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function menuActions() : BelongsToMany
    {
        return $this->belongsToMany(MenuAction::class, TreeMenuAction::tableName(), 'tree_id', 'menu_action', 'id', 'menu_action');
    }
    
    /**
     * 查询所有正常数据
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function selectAll()
    {
        return $this->newQuery()
            ->orderBy('parent_id', 'asc')
            ->orderBy('sort', 'asc')
            ->get();
    }

    /**
     * 查询所有正常数据
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function selectAllById($id)
    {
        if (null == ($tree = $this->newQuery()->find($id))) {
            return $this->newCollection();
        }

        return $this->newQuery()->where(function ($query) use ($tree) {
            $query->where('path', 'like', $tree->path . '.%')->orWhere('id', '=', $tree->id);
        })
            ->orderBy('parent_id', 'asc')
            ->orderBy('sort', 'asc')
            ->get();
    }

    /**
     * 查询所有子孙节点
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function selectChildById($id)
    {
        if (null == ($tree = $this->newQuery()->find($id))) {
            return $this->newCollection();
        }
        
        return $this->newQuery()->where('path', 'like', $tree->path . '.%')
            ->orderBy('parent_id', 'asc')
            ->orderBy('sort', 'asc')
            ->get();
    }
}
