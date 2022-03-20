<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                // Handle common error
                return response()->json([
                    'error' => [
                        'code' => 'unauthorized',
                        'message' => 'Unauthorized',
                        'detail' => null,
                    ]
                ], Response::HTTP_UNAUTHORIZED);
            }
        });

        $this->renderable(function (ValidationException $e, $request) {
            if ($request->is('api/*')) {
                // Handle common error
                return response()->json([
                    'error' => [
                        'code' => 'validation_error',
                        'message' => 'Validation error',
                        'detail' => $e->errors(),
                    ]
                ], Response::HTTP_BAD_REQUEST);
            }
        });

        $this->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                // Handle common error
                return response()->json([
                    'error' => [
                        'code' => 'system_error',
                        'message' => 'System error',
                        'detail' => $e->getMessage(),
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        });
    }
}
