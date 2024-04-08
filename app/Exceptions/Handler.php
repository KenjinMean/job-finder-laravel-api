<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler {
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
    public function register(): void {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception) {
        if ($request->expectsJson() || $request->is('api/*')) {
            // Return JSON response for API requests
            return $this->handleApiExceptions($request, $exception);
        }

        return parent::render($request, $exception);
    }

    protected function handleApiExceptions($request, Throwable $exception) {
        if ($exception instanceof ValidationException) {
            return ResponseHelper::generateErrorResponse($exception, "Validation Error", Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($exception instanceof ModelNotFoundException) {
            return ResponseHelper::generateErrorResponse($exception, "Resource not found", Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof AuthorizationException) {
            return ResponseHelper::generateErrorResponse($exception, "Unauthorized", Response::HTTP_FORBIDDEN);
        }

        if ($exception instanceof HttpException) {
            $statusCode = $exception->getStatusCode();
            $errorMessage = "An error occurred while processing your request. The server responded with an HTTP $statusCode error. Please check your request and try again. If the problem persists, contact support for assistance.";
            return ResponseHelper::generateErrorResponse($exception, $errorMessage, $statusCode);
        }

        return ResponseHelper::generateErrorResponse($exception, "Something went wrong", Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
