<?php

namespace App\Objects;

final class ResultCardResponseBodyObject
{
    public $serial;
    public $pin;

    public function __construct($serial, $pin)
    {
        $this->serial = $serial;
        $this->pin = $pin;
    }
}
