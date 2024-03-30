<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ResponseHelper {
  public static function errorResponse($message, $statusCode = Response::HTTP_BAD_REQUEST, $error = null, $file = null, $line = null): JsonResponse {
    return response()->json([
      'message' => $message,
      'error' => $error,
      'file' => $file,
      'line' => $line,
    ], $statusCode);
  }

  public static function generateErrorResponse($e, $statusCode = Response::HTTP_BAD_REQUEST, $customParameters = []) {
    $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : $statusCode;

    $responseData = [
      "error" => [
        "message" => $e->getMessage()
      ]
    ];

    $responseData = array_merge($responseData, $customParameters);

    Log::channel('dev')->debug("Error has occurred", [
      'code' => $e->getCode(),
      'message' => $e->getMessage(),
      'file' => $e->getFile(),
      'line' => $e->getLine(),
      'additional_parameter' => $customParameters,
    ]);

    return response()->json($responseData, $statusCode);
  }
}
