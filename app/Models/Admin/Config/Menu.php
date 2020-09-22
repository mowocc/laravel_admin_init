<?php
namespace App\Models\Admin\Config;

use App\Models\Admin\Model;
use App\Models\Traits\Admin\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Observers\Admin\Config\MenuObserver;

class Menu extends Model
{
    use SoftDeletes, LogsActivity;

    protected static function booted()
    {
        static::observe(MenuObserver::class);
    }
    
    /**
     * 数据表名称
     *
     * @var string
     */
    protected $table = 'config_menus';
    
    /**
     * 可以批量赋值的字段
     *
     * @var array
     */
    protected $fillable = [
        'sort',
        'parent_id',
        'path',
        'name',
        'icon',
        'route_path',
        'route_name'
    ];
    
    /**
     * 获取页面的功能
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actions()
    {
        return $this->hasMany(MenuAction::class, 'menu_id', 'id');
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
     * 查询多条数据【路由名称】
     *
     * @param array $routeNames
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function selectByRouteNames($routeNames)
    {
        return $this->newQuery()->whereIn('route_name', $routeNames)
            ->orderBy('parent_id', 'asc')
            ->orderBy('sort', 'asc')
            ->get();
    }
    
    /**
     * 制作目录菜单叶子节点
     *
     * @param \Illuminate\Database\Eloquent\Collection $menus
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function makeMenusLeaf($menus)
    {
        return $menus->keyBy('id')->diffKeys($menus->keyBy('parent_id'));
    }
}
