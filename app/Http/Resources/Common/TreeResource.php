<?php

namespace App\Http\Resources\Common;

use App\Http\Resources\Resource;

class TreeResource extends Resource
{

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = $this->makeTree(parent::toArray($request));

        return $this->getResponse()->setResponseData($data);
    }

    /**
     * 转换为树形结构
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toTree($request)
    {
        return $this->makeTree(parent::toArray($request));
    }

    /**
     * 制作树结构（处理前必须已经按照 $parent 排序）
     *
     * @param array $datas
     * @param string $primary
     * @param string $parent
     * @param string $children
     * @return array
     */
    protected function makeTree(array $datas, $primary = 'id', $parent = 'parent_id', $children = 'children')
    {
        $tree = array();

        $datas = array_column($datas, null, $primary);

        foreach ($datas as $item) {
            if (isset($datas[$item[$parent]])) {
                $datas[$item[$parent]][$children][] = &$datas[$item[$primary]];
            } else {
                $tree[] = &$datas[$item[$primary]];
            }
        }

        return $tree;
    }
}
