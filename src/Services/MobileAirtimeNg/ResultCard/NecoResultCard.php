<?php

namespace App\Services\MobileAirtimeNg\ResultCard;

use App\Interfaces\ResultCard\NecoResultCardInterface;
use App\Objects\ResultCardResponseBodyObject;
use App\Responses\ResultCard\ResultCardResponse;
use App\Services\MobileAirtimeNg\ResultCardTraits;

class NecoResultCard implements NecoResultCardInterface
{

    use ResultCardTraits;

    public function buyResultCard(int $user_id = 0): ResultCardResponse
    {
        //assign trans_ref
        $trans_ref = uniqid($user_id . "_");

        $process = $this->NecoResultCard($trans_ref);

        if (!empty($process['success'])) {
            $process['trans_ref'] = $trans_ref;
        }

        return new ResultCardResponse(
            $process['success'],
            $process['message'],
            $process['code'],
            $process['platform_id'],
            $process['trans_ref'],
            new ResultCardResponseBodyObject(
                $process['body']->serial,
                $process['body']->pin
            )
        );
    }
}
