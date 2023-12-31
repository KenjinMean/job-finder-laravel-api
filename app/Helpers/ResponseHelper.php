<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ResponseHelper {
  public static function errorResponse($message, $statusCode = Response::HTTP_BAD_REQUEST, $error = null, $file = null, $line = null): JsonResponse {
    return response()->json([
      'message' => $message,
      'error' => $error,
      'file' => $file,
      'line' => $line,
    ], $statusCode);
  }
}
