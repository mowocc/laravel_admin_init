<?php
namespace App\Exceptions\Response;

use Exception;
use App\Core\Response\ResponseError;

/**
 * 自定义 Validation 异常，及统一封装处理
 */
class ValidationException extends Exception
{

    /**
     * 表单验证错误
     */
    const VALIDATION_ERROR = 42000;

    /**
     * 详细错误信息
     *
     * @var array
     */
    protected $errors = [];

    /**
     * 构造并初始化参数
     *
     * @param int $code            
     * @param string $message            
     */
    public function __construct(?int $code = self::VALIDATION_ERROR, ?string $message = '', array $errors = [])
    {
        $code = $code ?? self::VALIDATION_ERROR;
        
        $message = $message ?: trans('exception.validation.' . $code);
        
        parent::__construct($message, $code);
        
        $this->setErrors($errors);
    }

    /**
     * 设置详细错误信息
     *
     * @param array $errors            
     * @return $this
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
        
        return $this;
    }

    /**
     * 获取详细错误信息
     *
     * @param array $errors            
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
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
        return new ResponseError($this->getCode(), $this->getMessage(), $this->getErrors());
    }
}