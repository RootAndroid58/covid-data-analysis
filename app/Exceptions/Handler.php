<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Helpers\ApiHelper;

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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(ApiHelper::SuccessorFail(404,array("error" => $e->getMessage())));
             }
        });
        $this->reportable(function (Throwable $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(ApiHelper::SuccessorFail(500,array("error" => $e->getMessage())));
             }
        });
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception ){
        if ($request->expectsJson()) {
            if ($exception instanceof NotFoundHttpException) {
                return response()->json(ApiHelper::SuccessorFail(404,array("error" => $exception->getMessage())));
            }
            if ($exception instanceof ModelNotFoundException) {
                return response()->json(ApiHelper::SuccessorFail(404,array("error" => $exception->getMessage())));
            }
            if ($exception instanceof MethodNotAllowedHttpException) {
                return response()->json(ApiHelper::SuccessorFail(400,array("error" => $exception->getMessage())));
            }
            if($exception instanceof \Error){
                response()->json(ApiHelper::SuccessorFail(500,array("error" => $exception->getMessage())));
            }
            if($exception instanceof \Throwable){
                response()->json(ApiHelper::SuccessorFail(500,array("error" => $exception->getMessage())));
            }
        }
        if($exception instanceof \Throwable){
            response()->json(ApiHelper::SuccessorFail(500,array("error" => $exception->getMessage())));
        }
        return parent::render($request, $exception);
    }

}
