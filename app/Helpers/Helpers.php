<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class Helpers {
  /**
   * Log a debug message to the development channel.
   *
   * @param string $message The message to log.
   * @param array $context  Optional context data to include in the log.
   * @return void
   */
  public static function devLog($message, $context = []) {
    Log::channel('dev')->debug($message, $context);
  }
}
