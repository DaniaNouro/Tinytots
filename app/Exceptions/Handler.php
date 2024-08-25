<?php

namespace App\Exceptions;

use App\Http\Responces\Response;
use Carbon\Exceptions\InvalidArgumentException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
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
     //   $this->reportable(function (AccessDeniedException $e,$request) {
        $this->renderable(function (AccessDeniedHttpException $e, $request){
           return Response::Error('','you do not have the required authorization',403);
        });
        $this->renderable(function (ValidationException $exception, $request) {
            return Response::Validation(' ', $exception->validator->errors(),422);
                
        });
        $this->renderable(function (NotFoundHttpException $e,$request){
            if ($request->is('api/admine/searchByName')) {
                return Response::Validation('','No text was enterd to search for', 404);
            }
        });
}
}