<?php

namespace App\Objects;

final class PaymentLinkDetailsObject
{
    public $name;
    public $phone;
    public $email;
    public $redirect_url;

    public function __construct($name, $phone, $email, $redirect_url)
    {
        $this->name = $name;
        $this->phone = $phone;
        $this->email = $email;
        $this->redirect_url = $redirect_url;
    }
}
