<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        CustomException::class,
        FaucetCustomException::class,
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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        //发送异常报告到Sentry
        if (app()->bound('sentry') && $this->shouldReport($exception) && !config('app.debug')) {
            app('sentry')->captureException($exception);
        }

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
        return parent::render($request, $exception);
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errorCode = config('exceptions.ValidationException');
        $errors = $e->errors();
        $errorFields = array_keys($e->errors());    //报错的字段
        $errorMsg = $errors[$errorFields[0]][0];    //只显示第一个字段的第一条报错信息

        return response()->json([
            'success' => false,
            'code' => $errorCode,
            'error' => $errorMsg,
        ], 400, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        $adminDomain = config('app.admin_domain');

        switch ($request->getHost()) {
            case $adminDomain:
//                return $request->expectsJson()
//                    ? response()->json(['message' => $exception->getMessage()], 401)
//                    : redirect()->guest(route('/'));
                //不管有没有accept头部都返回json
                return response()->json(['message' => $exception->getMessage()], 401);
                break;
            default:
//                return $request->expectsJson()
//                    ? response()->json(['message' => $exception->getMessage()], 401)
//                    : redirect()->guest('/');
                return response()->json(['message' => $exception->getMessage()], 401);
        }
    }
}
