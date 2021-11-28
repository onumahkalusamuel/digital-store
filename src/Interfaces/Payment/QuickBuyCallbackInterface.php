<?php

namespace App\Interfaces\Payment;

interface QuickBuyCallbackInterface
{

    public function process(): array;
}
