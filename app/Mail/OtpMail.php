<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable {
    use Queueable, SerializesModels;

    public $user;
    public $otpCode;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $otpCode) {
        $this->user = $user;
        $this->otpCode = $otpCode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->from(env('MAIL_FROM_ADDRESS'))
            ->subject('Your OTP Code')
            ->view('emails.otp')
            ->with([
                'user' => $this->user,
                'otpCode' => $this->otpCode,
            ]);
    }
}
