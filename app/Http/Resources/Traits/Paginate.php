<?php

namespace App\Http\Resources\Traits;

trait Paginate
{

    /**
     * 分页数据总条数
     *
     * @var int
     */
    private $total;

    /**
     * 设置分页数据总条数
     *
     * @param int $total
     * @return $this
     */
    public function setPaginateTotal(int $total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * 获取分页数据总条数
     *
     * @return int
     */
    protected function getPaginateTotal()
    {
        return $this->total;
    }

    /**
     * 验证是否启用分页
     *
     * @return boolean
     */
    protected function isValidPaginate()
    {
        return $this->total !== null;
    }

    /**
     * 分页页码
     *
     * @var int
     */
    private $page;

    /**
     * 设置分页页码
     *
     * @param int $page
     * @return $this
     */
    public function setPaginatePage(int $page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * 获取分页页码
     *
     * @param  \Illuminate\Http\Request $request
     * @return int
     */
    protected function getPaginatePage($request)
    {
        return $this->page ?: (int)($request->input('page', 1) ?: 1);
    }

    /**
     * 分页每页条数
     *
     * @var int
     */
    private $pageSize;

    /**
     * 设置分页每页条数
     *
     * @param int $pageSize
     * @return $this
     */
    public function setPaginatePageSize(int $pageSize)
    {
        $this->pageSize = $pageSize;

        return $this;
    }

    /**
     * 获取分页每页条数
     *
     * @param  \Illuminate\Http\Request $request
     * @return int
     */
    protected function getPaginatePageSize($request)
    {
        return $this->pageSize ?: (int)($request->input('page_size', 10) ?: 10);
    }

    /**
     * 获取分页信息
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    protected function getPaginate($request)
    {
        return [
            'total' => $this->getPaginateTotal(),
            'page' => $this->getPaginatePage($request),
            'page_size' => $this->getPaginatePageSize($request),
        ];
    }
}
