<?php

namespace App\Exceptions;

use App\Elibs\eBug;
use App\Http\Models\Member;
use App\Http\Models\Staff;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
    static $__countEx = false;
    public function report(Exception $exception)
    {
        //dd($exception);
        $statusCode = 0;
        if($exception instanceof NotFoundHttpException) {
            $statusCode = $exception->getStatusCode();
        }
        if (!self::$__countEx && !in_array($statusCode,[404,300])) {
            self::$__countEx = true;
            $msg = "User:======" ;
//            $msg .= "\nAccount Email:".Staff::getCurrentEmail() ;
//            $msg .= "\nAccount Name:".Staff::getCurrentName();
//            $msg .= "\nAccount:".Staff::getCurentAccount();
//            $msg .= "\nUser:======" ;
            $msg .= "\n\nMessage: " . $exception->getMessage();
            $msg .= "\nStatusCode: " . $statusCode;
            $msg .= "\nFile: " . $exception->getFile() . ':' . $exception->getLine();
            $msg .= "\nREQUEST_URI: " . @$_SERVER['REQUEST_URI'];
            $msg .= "\nREMOTE_ADDR: " . @$_SERVER['REMOTE_ADDR'];
            $msg .= "\nHTTP_USER_AGENT: " . @$_SERVER['HTTP_USER_AGENT'];
            $msg .= "\nHTTP_REFERER: " . @$_SERVER['HTTP_REFERER'];
            $msg .= "\nREQUEST_METHOD: " . @$_SERVER['REQUEST_METHOD'];
            $msg .= "\nSERVER_NAME: " . @$_SERVER['SERVER_NAME'];
            $msg .= "\nHTTP_HOST: " . @$_SERVER['HTTP_HOST'];
            eBug::pushNotification($msg);
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
}
