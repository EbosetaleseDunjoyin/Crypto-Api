<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    // public function register(): void
    // {
    //     $this->reportable(function (Throwable $e) {
    //         //
    //     });
    // }
    public function render($request, Throwable $exception) 
    {
        
        if ($exception instanceof NotFoundHttpException ) {
            return response()->json([
                'status' => false,
                'message' => 'Resource not found / Route not found'
            ], 404);
        }

        if (($exception instanceof QueryException ) || ($exception instanceof ModelNotFoundException)  ) {
            return response()->json([
                'status' => false,
                'message' => 'Resource not found'
            ], 400);
        }

        // if ($exception instanceof AuthenticationException &&  $request->wantsJson()) {
        if (($exception instanceof AuthenticationException) || ($exception instanceof AccessDeniedHttpException)) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated \ Unauthorized.'
            ], 403);
        }

        // if($exception){
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Issue occured'
        //     ], 400);
        // }

        return parent::render($request, $exception);
    }
}
