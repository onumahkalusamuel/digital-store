<?php

namespace App\Interfaces\Payment;

use App\Objects\PaymentLinkDetailsObject;
use App\Responses\Payment\GeneratePaymentLinkResponse;
use App\Responses\Payment\PaymentVerificationResponse;

interface QuickBuyPaymentInterface
{
    public function generatePaymentLink(string $reference, float $amount, PaymentLinkDetailsObject $details): GeneratePaymentLinkResponse;
    public function paymentVerification(string $reference): PaymentVerificationResponse;
}
