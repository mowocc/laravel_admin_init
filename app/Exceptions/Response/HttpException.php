<?php
namespace App\Exceptions\Response;

use Exception;
use App\Core\Response\ResponseError;

/**
 * 自定义 Http 异常，及统一封装处理
 *
 * @see \Symfony\Component\HttpFoundation\Response
 */
class HttpException extends Exception
{

    const HTTP_BAD_REQUEST = 400;

    const HTTP_UNAUTHORIZED = 401;

    const HTTP_PAYMENT_REQUIRED = 402;

    const HTTP_FORBIDDEN = 403;

    const HTTP_NOT_FOUND = 404;

    const HTTP_METHOD_NOT_ALLOWED = 405;

    const HTTP_NOT_ACCEPTABLE = 406;

    const HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;

    const HTTP_REQUEST_TIMEOUT = 408;

    const HTTP_CONFLICT = 409;

    const HTTP_GONE = 410;

    const HTTP_LENGTH_REQUIRED = 411;

    const HTTP_PRECONDITION_FAILED = 412;

    const HTTP_REQUEST_ENTITY_TOO_LARGE = 413;

    const HTTP_REQUEST_URI_TOO_LONG = 414;

    const HTTP_UNSUPPORTED_MEDIA_TYPE = 415;

    const HTTP_REQUESTED_RANGE_NOT_SATISFIABLE = 416;

    const HTTP_EXPECTATION_FAILED = 417;

    const HTTP_I_AM_A_TEAPOT = 418;

    const HTTP_MISDIRECTED_REQUEST = 421;

    const HTTP_UNPROCESSABLE_ENTITY = 422;

    const HTTP_LOCKED = 423;

    const HTTP_FAILED_DEPENDENCY = 424;

    const HTTP_TOO_EARLY = 425;

    const HTTP_UPGRADE_REQUIRED = 426;

    const HTTP_PRECONDITION_REQUIRED = 428;

    const HTTP_TOO_MANY_REQUESTS = 429;

    const HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE = 431;

    const HTTP_UNAVAILABLE_FOR_LEGAL_REASONS = 451;

    const HTTP_INTERNAL_SERVER_ERROR = 500;

    const HTTP_NOT_IMPLEMENTED = 501;

    const HTTP_BAD_GATEWAY = 502;

    const HTTP_SERVICE_UNAVAILABLE = 503;

    const HTTP_GATEWAY_TIMEOUT = 504;

    const HTTP_VERSION_NOT_SUPPORTED = 505;

    const HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL = 506;

    const HTTP_INSUFFICIENT_STORAGE = 507;

    const HTTP_LOOP_DETECTED = 508;

    const HTTP_NOT_EXTENDED = 510;

    const HTTP_NETWORK_AUTHENTICATION_REQUIRED = 511;

    /**
     * 构造并初始化参数
     *
     * @param int $code            
     * @param string $message            
     */
    public function __construct(?int $code = self::HTTP_BAD_REQUEST, string $message = '')
    {
        $code = $code ?? self::HTTP_BAD_REQUEST;
        
        $message = $message ?: trans('exception.http.' . $code);
        
        parent::__construct($message, $code);
    }

    /**
     * 报告异常
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