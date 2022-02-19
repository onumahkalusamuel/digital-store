<?php

namespace App\Interfaces\Payment;

use App\Objects\PaymentLinkDetailsObject;
use App\Responses\Payment\GeneratePaymentLinkResponse;
use App\Responses\Payment\PaymentVerificationResponse;

interface AccountTopUpPaymentInterface
{
    public function generatePaymentLink(string $reference, float $amount, PaymentLinkDetailsObject $details): GeneratePaymentLinkResponse;
    public function paymentVerification($reference): PaymentVerificationResponse;
}
