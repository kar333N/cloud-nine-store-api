<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
     * @param Exception $exception
     * @return mixed|void
     * @throws Exception
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
    public function render($request, Exception $e)
    {
        if (
            $e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException ||
            $e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
        ) {
            return response(['error' => '404 File Not Found','message'=>$e->getMessage()], 404);
        }

        if ($e instanceof \Illuminate\Database\QueryException ||
            $e instanceof \Symfony\Component\Debug\Exception\FatalThrowableError ||
            $e instanceof \InvalidArgumentException
        ) {
            return response(['error' => '400 Bad Request','message'=>$e->getMessage()], 400);
        }


        return parent::render($request, $e);

    }
}
