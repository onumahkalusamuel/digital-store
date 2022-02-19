<?php

namespace App\Responses\Payment;


final class GeneratePaymentLinkResponse
{
    public $success;
    public $message;
    public $payment_link;
    public $platform_id;

    public function __construct($success = false, $message = "", $payment_link = "", $platform_id = "")
    {
        $this->success = $success;
        $this->message = $message;
        $this->payment_link = $payment_link;
        $this->platform_id = $platform_id;
    }
}
