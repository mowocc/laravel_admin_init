<?php
namespace App\Models\Admin\Config;

use App\Models\Admin\Model;
use App\Models\Traits\Admin\LogsActivity;

class MenuAction extends Model
{
    use LogsActivity;

    /**
     * 数据表名称
     *
     * @var string
     */
    protected $table = 'config_menu_actions';

    /**
     * 可以批量赋值的字段
     *
     * @var array
     */
    protected $fillable = [
        'menu_id',
        'menu_action',
        'action',
        'name',
        'type'
    ];

    /**
     * 类型【系统】
     *
     * @var string
     */
    const TYPE_SYSTEM = 'system';

    /**
     * 类型【自定义】
     *
     * @var string
     */
    const TYPE_CUSTOM = 'custom';

    /**
     * 查询多条数据【页面功能】
     *
     * @param array $menuActions            
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function selectByMenuActions($menuActions)
    {
        return $this->newQuery()
            ->whereIn('menu_action', $menuActions)
            ->get();
    }
}
