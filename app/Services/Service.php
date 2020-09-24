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
     * @param \Illuminate\Database\Eloquent\Builder $query            
     * @param string $column            
     * @param string $operator            
     * @return $this
     */
    protected function makeWhereParam($request, $key, $query, $column, $operator = '=')
    {
        if ($this->isValidParam($request, $key)) {
            $query->where($column, $operator, $request->input($key));
        }
        return $this;
    }

    /**
     * 制作查询参数【like】
     *
     * @param \Illuminate\Http\Request $request            
     * @param string $key            
     * @param \Illuminate\Database\Eloquent\Builder $query            
     * @param string $column            
     * @param boolean $isLeft            
     * @return $this
     */
    protected function makeWhereParamLike($request, $key, $query, $column, $isLeft = true)
    {
        if ($this->isValidParam($request, $key)) {
            $query->where($column, 'like', ($isLeft ? '' : '%') . $request->input($key) . '%');
        }
        return $this;
    }

    /**
     * 制作查询参数【软删除】
     *
     * @param \Illuminate\Http\Request $request            
     * @param \Illuminate\Database\Eloquent\Builder $query            
     * @return $this
     */
    protected function makeWhereDeleted($request, $query)
    {
        // 软删除筛选
        if (! $this->isValidParam($request, 'deleted')) {
            $query->withTrashed();
        } elseif ((boolean) $request->input('deleted')) {
            $query->onlyTrashed();
        }
        return $this;
    }
}