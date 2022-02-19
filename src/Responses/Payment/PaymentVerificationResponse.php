<?php

namespace App\Responses\Payment;


final class PaymentVerificationResponse
{
    public $success;
    public $message;
    public $amount;

    public function __construct($success = false, $message = "", $amount = 0)
    {
        $this->success = $success;
        $this->message = $message;
        $this->amount = $amount;
    }
}
