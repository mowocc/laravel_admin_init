<?php
namespace App\Exceptions\Response;

use Exception;
use App\Core\Response\ResponseError;

/**
 * 自定义 System 异常，及统一封装处理
 */
class SystemException extends Exception
{

    /**
     * 系统错误
     */
    const SYSTEM_ERROR = 49000;

    /**
     * 构造并初始化参数
     *
     * @param int $code            
     * @param string $message            
     */
    public function __construct(?int $code = self::SYSTEM_ERROR, string $message = '')
    {
        $code = $code ?? self::SYSTEM_ERROR;
        
        $message = $message ?: trans('exception.system.' . $code);
        
        parent::__construct($message, $code);
    }

    /**
     * 记录异常日志
     *
     * @return void
     */
    public function report()
    {
        //
    }

    /**
     * 将异常渲染到 HTTP 响应中
     *
     * @param \Illuminate\Http\Request $request            
     * @return \App\Core\Response\ResponseError
     */
    public function render($request)
    {
        return new ResponseError($this->getCode(), $this->getMessage());
    }
}