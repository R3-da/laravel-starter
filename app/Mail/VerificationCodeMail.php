<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class VerificationCodeMail extends Mailable
{
    public $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function build()
    {
        return $this->view('emails.verification-code')
                    ->subject('Email Verification Code');
    }
}
