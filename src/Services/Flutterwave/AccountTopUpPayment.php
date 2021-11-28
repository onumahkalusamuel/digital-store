<?php

namespace App\Services\Flutterwave;

use App\Interfaces\Payment\AccountTopUpPaymentInterface;

class AccountTopUpPayment implements AccountTopUpPaymentInterface
{
    use FlutterwaveTraits;
}
