<?php

namespace App\Interfaces\Payment;

interface QuickBuyPaymentInterface
{
    public function generatePaymentLink($reference, $amount, $details): array;
    public function paymentVerification($reference): array;
}
