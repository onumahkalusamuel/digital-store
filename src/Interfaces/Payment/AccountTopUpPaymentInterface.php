<?php

namespace App\Interfaces\Payment;

interface AccountTopUpPaymentInterface
{
    public function generatePaymentLink($reference, $amount, $details): array;
    public function paymentVerification($reference): array;
}
