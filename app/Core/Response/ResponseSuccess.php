<?php
namespace App\Core\Response;

/**
 * Response 成功实体对象
 */
class ResponseSuccess extends Response
{

    public function __construct(array $data = [])
    {
        $this->setResponseMessage()->setResponseData($data);
    }

    /**
     * 可链式操作的工厂方法
     *
     * @param array $data            
     * @return static
     */
    public static function make(array $data = [])
    {
        return new static($data);
    }

    /**
     * 设置 Response 成功信息
     *
     * @return $this
     */
    protected function setResponseMessage()
    {
        $this->offsetSet('resp_msg', [
            'code' => 200,
            'message' => 'success'
        ]);
        
        return $this;
    }

    /**
     * 设置 Response 数据
     *
     * @param array $data            
     * @return $this
     */
    public function setResponseData(array $data = [])
    {
        $this->offsetSet('resp_data', $data);
        
        return $this;
    }
}