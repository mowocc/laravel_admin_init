<?php
namespace Seeds\Admin\Config;

use Illuminate\Database\Seeder;
use App\Models\Admin\Config\Menu;

class MenuSeeder extends Seeder
{

    private $parent = [
        'id' => 0,
        'path' => '0'
    ];

    private $id = 1;

    private $dataList = [];

    private $menuTree = [
        [
            'name' => '首页',
            'icon' => 'chart-area',
            'route_path' => '/index',
            'route_name' => 'index'
        ],
        [
            'name' => '统计',
            'icon' => 'list-alt',
            'route_path' => '/chart',
            'route_name' => 'chart'
        ],
        [
            'name' => '代理',
            'icon' => 'sitemap',
            'route_path' => '/agency',
            'route_name' => 'agency.index',
            'children' => [
                [
                    'name' => '代理管理',
                    'icon' => 'cog',
                    'route_path' => '/agency/setting',
                    'route_name' => 'agency.setting'
                ],
                [
                    'name' => '成员管理',
                    'icon' => 'user-friends',
                    'route_path' => '/agency/contacts',
                    'route_name' => 'agency.contacts'
                ]
            ]
        ],
        [
            'name' => '游戏',
            'icon' => 'gamepad',
            'route_path' => '/game',
            'route_name' => 'game'
        ],
        [
            'name' => '订单',
            'icon' => 'clipboard-list',
            'route_path' => '/order',
            'route_name' => 'order.index',
            'children' => [
                [
                    'name' => '游戏订单',
                    'icon' => 'clipboard-list',
                    'route_path' => '/order/game',
                    'route_name' => 'order.game'
                ],
                [
                    'name' => '钱包订单',
                    'icon' => 'file-alt',
                    'route_path' => '/order/wallet',
                    'route_name' => 'order.wallet'
                ]
            ]
        ],
        [
            'name' => '系统',
            'icon' => 'cogs',
            'route_path' => '/system',
            'route_name' => 'system.index',
            'children' => [
                [
                    'name' => '系统配置',
                    'icon' => 'cog',
                    'route_path' => '/system/setting',
                    'route_name' => 'system.setting.index',
                    'children' => [
                        [
                            'name' => '语言管理',
                            'icon' => '',
                            'route_path' => '/system/setting/language',
                            'route_name' => 'system.setting.language'
                        ],
                        [
                            'name' => '分区配置',
                            'icon' => '',
                            'route_path' => '/system/setting/server',
                            'route_name' => 'system.setting.server'
                        ],
                        [
                            'name' => '目录菜单',
                            'icon' => '',
                            'route_path' => '/system/setting/menu',
                            'route_name' => 'system.setting.menu'
                        ]
                    ]
                ],
                [
                    'name' => '后台用户',
                    'icon' => 'user',
                    'route_path' => '/system/user',
                    'route_name' => 'system.user'
                ],
                [
                    'name' => '权限设置',
                    'icon' => 'user-shield',
                    'route_path' => '/system/permission',
                    'route_name' => 'system.permission'
                ],
                [
                    'name' => '活动日志',
                    'icon' => 'file-alt',
                    'route_path' => '/system/log',
                    'route_name' => 'system.log.index',
                    'children' => [
                        [
                            'name' => '操作日志',
                            'icon' => '',
                            'route_path' => '/system/log/activity',
                            'route_name' => 'system.log.activity'
                        ],
                        [
                            'name' => '登录日志',
                            'icon' => '',
                            'route_path' => '/system/log/login',
                            'route_name' => 'system.log.login'
                        ]
                    ]
                ],
                [
                    'name' => '统计任务',
                    'icon' => 'tasks',
                    'route_path' => '/system/task',
                    'route_name' => 'system.task.index',
                    'children' => [
                        [
                            'name' => '游戏统计',
                            'icon' => '',
                            'route_path' => '/system/task/stats',
                            'route_name' => 'system.task.stats'
                        ],
                        [
                            'name' => '玩家留存',
                            'icon' => '',
                            'route_path' => '/system/task/retention',
                            'route_name' => 'system.task.retention'
                        ]
                    ]
                ],
                [
                    'name' => '游戏测试',
                    'icon' => 'bug',
                    'route_path' => '/system/testing',
                    'route_name' => 'system.testing'
                ]
            ]
        ]
    ];

    /**
     * 递归制作数据
     *
     * @param array $menuTree
     * @param array $parent
     */
    protected function makeDataLoop($menuTree = [], $parent = [])
    {
        foreach ($menuTree as $item) {
            $children = [];
            // 父级ID
            $item['parent_id'] = $parent['id'];
            // ID/排序
            $item['id'] = $item['sort'] = $this->id ++;
            // 依赖路径
            $item['path'] = $parent['path'] . '.' . $item['id'];
            // 时间
            $item['created'] = $item['updated'] = time();

            // 子级
            if (isset($item['children'])) {
                $children = $item['children'];
                unset($item['children']);
            }
            // 追加一条数据
            $this->dataList[] = $item;

            // 递归
            if (! empty($children)) {
                $this->makeDataLoop($children, $item);
            }
        }
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 制作数据
        $this->makeDataLoop($this->menuTree, $this->parent);
        
        // 插入数据
        Menu::dbTable()->insert($this->dataList);
    }

    /**
     * 获取菜单数据
     *
     * @return array
     */
    public function getDataList()
    {
        // 制作数据
        $this->makeDataLoop($this->menuTree, $this->parent);

        return $this->dataList;
    }

    /**
     * 获取菜单数据
     *
     * @return array
     */
    public static function getMenus()
    {
        return (new static())->getDataList();
    }
}
