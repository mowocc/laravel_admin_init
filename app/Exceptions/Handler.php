<?php
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
// 拦截异常
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
// 拦截异常的自定义封装
use App\Exceptions\Response\HttpException as ResponseHttpException;
use App\Exceptions\Response\ServiceException as ResponseServiceException;
use App\Exceptions\Response\SystemException as ResponseSystemException;
use App\Exceptions\Response\ValidationException as ResponseValidationException;

class Handler extends ExceptionHandler
{

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation'
    ];

    /**
     * Report or log an exception.
     *
     * @param \Throwable $exception            
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request            
     * @param \Throwable $exception            
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            if ($exception instanceof AuthenticationException) {
                $exception = new ResponseHttpException(ResponseHttpException::HTTP_UNAUTHORIZED);
            } elseif ($exception instanceof AuthorizationException) {
                $exception = new ResponseHttpException(ResponseHttpException::HTTP_FORBIDDEN);
            } elseif ($exception instanceof ValidationException) {
                $exception = new ResponseValidationException(null, null, $exception->errors());
            } elseif ($exception instanceof ModelNotFoundException) {
                $exception = new ResponseServiceException(ResponseServiceException::SERVICE_NOT_EXIST_DATA);
            } elseif ($exception instanceof HttpException) {
                $exception = new ResponseHttpException($exception->getStatusCode());
            } elseif ($exception instanceof ResponseServiceException) {
                // 不需要再次封装
            } else {
                $exception = new ResponseSystemException(null, $exception->getMessage());
            }
        }
        return parent::render($request, $exception);
    }
}
