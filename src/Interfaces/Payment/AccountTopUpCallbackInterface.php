<?php

namespace App\Interfaces\Payment;

interface AccountTopUpCallbackInterface
{

    public function process(): array;
}
