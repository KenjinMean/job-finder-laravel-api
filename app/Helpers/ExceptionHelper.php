<?php

namespace App\Helpers;

use Throwable;
use TypeError;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;
use League\Flysystem\FilesystemException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class ExceptionHelper {
  public static function handleException(Throwable $e): JsonResponse {
    switch (true) {
      case $e instanceof ValidationException:
        return ResponseHelper::errorResponse('Validation error', Response::HTTP_UNPROCESSABLE_ENTITY, $e->getMessage());
      case $e instanceof QueryException:
        return ResponseHelper::errorResponse('An error occurred during the query execution', Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
      case $e instanceof AuthenticationException:
        return ResponseHelper::errorResponse('Authentication failed', Response::HTTP_UNAUTHORIZED, $e->getMessage());
      case $e instanceof AuthorizationException:
        return ResponseHelper::errorResponse('Unauthorized action', Response::HTTP_FORBIDDEN, $e->getMessage());
      case $e instanceof NotFoundHttpException:
        return ResponseHelper::errorResponse('Resource not found', Response::HTTP_NOT_FOUND, $e->getMessage());
      case $e instanceof MethodNotAllowedHttpException:
        return ResponseHelper::errorResponse('Method not allowed', Response::HTTP_METHOD_NOT_ALLOWED, $e->getMessage());
      case $e instanceof ModelNotFoundException:
        return ResponseHelper::errorResponse('Model not found', Response::HTTP_NOT_FOUND, $e->getMessage());
      case $e instanceof FilesystemException:
        return ResponseHelper::errorResponse('Filesystem error', Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
      case $e instanceof TypeError:
        return ResponseHelper::errorResponse('Type error occurred', Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
      default:
        return response()->json(['message' => 'An error occurred', "error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }
}
