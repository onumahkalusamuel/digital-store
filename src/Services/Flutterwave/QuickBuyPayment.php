<?php

namespace App\Services\Flutterwave;

use App\Interfaces\Payment\QuickBuyPaymentInterface;

class QuickBuyPayment implements QuickBuyPaymentInterface
{
    use FlutterwaveTraits;
}
