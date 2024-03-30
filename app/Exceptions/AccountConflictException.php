<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use Illuminate\Support\Facades\Log;

class AccountConflictException extends Exception {
    public $provider;

    public function __construct($message = 'Account Conflict', $provider = "", $code = 409, Throwable $previous = null) {
        $this->provider = $provider;
        parent::__construct($message, $code, $previous);

        Log::error('Account Conflict Exception: ' . $message, ['provider' => $provider]);
    }

    public function getProvider() {
        return $this->provider;
    }
}
