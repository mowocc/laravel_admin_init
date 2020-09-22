<?php
namespace App\Services;

class Service
{
    use ServiceTrait;

    /**
     * 获取分页页码
     *
     * @param \Illuminate\Http\Request $request            
     * @return int
     */
    protected function getPage($request)
    {
        return (int) $request->input('page', 1) ?: 1;
    }

    /**
     * 获取分页每页条数
     *
     * @param \Illuminate\Http\Request $request            
     * @return int
     */
    protected function getPageSize($request)
    {
        return (int) $request->input('page_size', 10) ?: 10;
    }

    /**
     * 判断参数是否有效
     *
     * @param \Illuminate\Http\Request $request            
     * @param string $key            
     * @return boolean
     */
    protected function isValidParam($request, $key)
    {
        // 空字符串
        if (! $request->filled($key)) {
            return false;
        }
        // 空数组
        if (is_array($request->input($key)) && empty($request->input($key))) {
            return false;
        }
        return true;
    }

    /**
     * 制作查询参数
     *
     * @param \Illuminate\Http\Request $request            
     * @param string $key            
     * @param array $where            
     * @param string $column            
     * @param string $operator            
     * @return $this
     */
    protected function makeWhereParam($request, $key, &$where, $column, $operator = '=')
    {
        if ($this->isValidParam($request, $key)) {
            array_push($where, [
                $column,
                $operator,
                $request->input($key)
            ]);
        }
        return $this;
    }

    /**
     * 制作查询参数【like】
     *
     * @param \Illuminate\Http\Request $request            
     * @param string $key            
     * @param array $where            
     * @param string $column            
     * @param boolean $isLeft            
     * @return $this
     */
    protected function makeWhereParamLike($request, $key, &$where, $column, $isLeft = true)
    {
        if ($this->isValidParam($request, $key)) {
            array_push($where, [
                $column,
                'like',
                ($isLeft ? '' : '%') . $request->input($key) . '%'
            ]);
        }
        return $this;
    }

    /**
     * 制作查询参数【直接赋值】
     *
     * @param array $where            
     * @param string $column            
     * @param string $value            
     * @param string $operator            
     * @return $this
     */
    protected function makeWhereValue(&$where, $column, $value, $operator = '=')
    {
        array_push($where, [
            $column,
            $operator,
            $value
        ]);
        return $this;
    }
}