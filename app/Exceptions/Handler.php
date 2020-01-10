<?php

namespace App\Exceptions;

use App\Classes\RestApi;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
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

    // function __construct(ResponseInterface $response)
    // {

    //     $this->response = $response;
    // }

    /**
     * Report or log an exception.
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
       //dd($exception);
        if ($request->is('api/*')) {
            try {
                if ($exception instanceof AuthenticationException) {
                    return RestApi::error('Unauthorized user.');
                }

                if ($exception instanceof NotFoundHttpException) {
                    return RestApi::error("Not Found");
                }

                if ($exception instanceof ValidationException) {
                    return RestApi::error(implode("<br>", Arr::flatten($exception->validator->messages()->getMessages())), 'validation_error');
                }


                return RestApi::error($exception->getMessage());


//                if ($exception instanceof MethodNotAllowedHttpException) {
//                    return RestApi::error($exception->getMessage());
//                }
//
//
//                if ($exception instanceof \ReflectionException) {
//                    return RestApi::error($exception->getMessage());
//                }
//
//                if ($exception instanceof \BadMethodCallException) {
//                    return RestApi::error($exception->getMessage());
//                }
//
//                if ($exception instanceof AuthorizationException) {
//                    return RestApi::error($exception->getMessage());
//                }
//
//                if ($exception instanceof QueryException) {
//                    return RestApi::error($exception->getMessage());
//                }



                //    if($exception instanceof \Symfony\Component\Debug\Exception\FatalThrowableError){
        //     return RestApi::error($exception->getMessage());
        //    }
            } catch (\Exception $e) {
                return RestApi::error($e->getMessage());
            }
        }

        return parent::render($request, $exception);
    }
}
