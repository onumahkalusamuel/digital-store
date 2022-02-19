<?php

namespace App\Responses\ResultCard;

use App\Objects\ResultCardResponseBodyObject;

final class ResultCardResponse
{
    public $success;
    public $message;
    public $code;
    public $platform_id;
    public $trans_ref;
    public $body;

    public function __construct(
        $success = false,
        $message = '',
        $code = 0,
        $platform_id = '',
        $trans_ref = '',
        ResultCardResponseBodyObject $body = null
    ) {
        $this->success = $success;
        $this->message = $message;
        $this->code = $code;
        $this->platform_id = $platform_id;
        $this->trans_ref = $trans_ref;
        $this->body = $body;
    }
}
