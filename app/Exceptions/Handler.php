<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {

        if ($exception instanceof  AuthenticationException ){
           return response(['msg'=>$exception->getMessage()]);
        }
        if($exception instanceof wxException) {
            $result = [
                "msg"   => '微信Api错误'.$exception->getMessage(),
                "code"   => $exception->getCode(),
                "status" => false,
            ];
            return response()->json($result,409);
        }
        // 用户认证的异常，我们需要返回 401 的 http code 和错误信息
        if ($exception instanceof UnauthorizedHttpException) {
            return response(['msg'=>$exception->getMessage()], 444);
        }
        if ($exception instanceof TokenInvalidException) {
            return response(['msg'=>$exception->getMessage()], 444);
        }
        if ($exception instanceof InvalidArgumentException) {
            return response($exception->getMessage(), 401);
        }


        return parent::render($request, $exception);
    }
}
