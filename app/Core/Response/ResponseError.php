<?php
namespace App\Core\Response;

/**
 * Response 失败实体对象
 */
class ResponseError extends Response
{

    public function __construct(?int $code = 400, ?string $message = '', array $errors = [])
    {
        $this->setResponseMessage($code, $message, $errors);
    }

    /**
     * 可链式操作的工厂方法
     *
     * @param int $code            
     * @param string $message            
     * @param array $errors            
     * @return static
     */
    public static function make(?int $code = 400, ?string $message = '', array $errors = [])
    {
        return new static($code, $message, $errors);
    }

    /**
     * 设置 Response 错误信息
     *
     * @param int $code            
     * @param string $message            
     * @param array $errors            
     * @return $this
     */
    public function setResponseMessage(?int $code, ?string $message, array $errors = [])
    {
        $this->offsetSet('resp_msg', [
            'code' => $code ?? 400,
            'message' => $message ?? 'error',
            'errors' => $errors
        ]);
        
        return $this;
    }
}