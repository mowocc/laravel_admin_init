<?php
namespace Seeds\Agency;

use Illuminate\Database\Seeder;
use App\Models\Agency\Agency\Org;

class OrgSeeder extends Seeder
{

    private $parent = [
        'id' => 0,
        'path' => '0'
    ];

    private $id = 1;

    private $dataList = [];

    private $orgTree = [
        [
            'name' => 'ABC',
            'children' => [
                [
                    'name' => 'test-1',
                    'children' => [
                        [
                            'name' => 'test-1-1'
                        ],
                        [
                            'name' => 'test-1-2'
                        ]
                    ]
                ],
                [
                    'name' => 'test-2'
                ]
            ]
        ],
        [
            'name' => 'DEF',
            'children' => [
                [
                    'name' => 'demo-1',
                    'children' => [
                        [
                            'name' => 'demo-1-1'
                        ],
                        [
                            'name' => 'demo-1-2'
                        ]
                    ]
                ],
                [
                    'name' => 'demo-2',
                    'children' => [
                        [
                            'name' => 'demo-2-1'
                        ]
                    ]
                ]
            ]
        ],
        [
            'name' => 'GHI',
            'children' => [
                [
                    'name' => 'hhh'
                ],
                [
                    'name' => 'iii'
                ]
            ]
        ]
    ];

    /**
     * 递归制作数据
     *
     * @param array $orgTree            
     * @param array $parent            
     */
    protected function makeDataLoop($orgTree = [], $parent = [])
    {
        foreach ($orgTree as $item) {
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
        $this->makeDataLoop($this->orgTree, $this->parent);
        
        // 插入数据
        Org::dbTable()->insert($this->dataList);
    }

    /**
     * 获取菜单数据
     *
     * @return array
     */
    public function getDataList()
    {
        // 制作数据
        $this->makeDataLoop($this->orgTree, $this->parent);
        
        return $this->dataList;
    }

    /**
     * 获取菜单数据
     *
     * @return array
     */
    public static function getOrgs()
    {
        return (new static())->getDataList();
    }
}
