<?php
namespace App\Services\System\Setting;

use App\Services\Service;
use App\Mappers\System\Setting\MenuMapper;
use App\Models\Admin\Config\Menu;

class MenuService extends Service
{

    /**
     * 获取数据列表
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Collection[]|int[]
     */
    public function getList($request)
    {
        $query = Menu::query();

        // 软删除筛选
        $this->makeWhereDeleted($request, $query);

        // 查询数据
        return $query
            ->orderBy('parent_id', 'asc')
            ->orderBy('sort', 'asc')
            ->get();
    }

    /**
     * 创建数据
     *
     * @param \Illuminate\Http\Request $request
     * @return App\Models\Admin\Config\Menu
     */
    public function store($request)
    {
        // 事物回滚
        return Menu::dbConnection()->transaction(function () use ($request) {
            // 创建数据
            $menu = Menu::create(
                MenuMapper::make($request)->toArray()
            );
            // 父级节点
            $parent = Menu::firstOrNew(['id' => $menu->parent_id], ['path' => '0']);
            // 更新关联字段
            $menu->sort = $menu->id;
            $menu->path = $parent->path . '.' . $menu->id;
            $menu->save();

            return $menu;
        });
    }

    /**
     * 更新数据
     *
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return App\Models\Admin\Config\Menu
     */
    public function update($id, $request)
    {
        // 事物回滚
        return Menu::dbConnection()->transaction(function () use ($id, $request) {
            // 目录菜单
            $menu = Menu::withTrashed()->findOrFail($id);
            // 修改数据
            $menu->fill(
                MenuMapper::make($request)->toArray()
            );
            // 保存数据
            $menu->save();
    
            return $menu;
        });
    }

    /**
     * 删除数据
     *
     * @param int $id
     * @return App\Models\Admin\Config\Menu
     */
    public function destroy($id)
    {
        // 事物回滚
        return Menu::dbConnection()->transaction(function () use ($id) {
            // 删除数据
            $menu = Menu::findOrFail($id);
            $menu->delete();

            // 删除子孙节点
            Menu::where('path', 'like', $menu->path . '.%')->delete();

            return $menu;
        });
    }

    /**
     * 恢复删除
     *
     * @param int $id
     * @return App\Models\Admin\Config\Menu
     */
    public function restore($id)
    {
        // 事物回滚
        return Menu::dbConnection()->transaction(function () use ($id) {
            // 恢复删除
            $menu = Menu::withTrashed()->findOrFail($id);
            $menu->restore();

            // 父级节点ID
            $ids = array_diff(explode('.', $menu->path), [0, $menu->id]);
            // 恢复父级节点
            Menu::withTrashed()->whereIn('id', $ids)->restore();

            return $menu;
        });
    }

    /**
     * 彻底删除
     *
     * @param int $id
     * @return App\Models\Admin\Config\Menu
     */
    public function forceDestroy($id)
    {
        // 事物回滚
        return Menu::dbConnection()->transaction(function () use ($id) {
            // 彻底删除
            $menu = Menu::withTrashed()->findOrFail($id);
            $menu->forceDelete();

            // 彻底删除子孙节点
            Menu::withTrashed()->where('path', 'like', $menu->path . '.%')->forceDelete();

            return $menu;
        });
    }
}
