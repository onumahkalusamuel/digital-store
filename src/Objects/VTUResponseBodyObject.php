<?php

namespace App\Objects;

final class VTUResponseBodyObject
{
    public $code;
    public $message;
    public $user_ref;
    public $batch_no;

    public function __construct($code, $message, $user_ref, $batch_no)
    {
        $this->code = $code;
        $this->message = $message;
        $this->user_ref = $user_ref;
        $this->$batch_no = $batch_no;
    }
}
