<?php

namespace App\Services\MobileAirtimeNg\ResultCard;

use App\Interfaces\ResultCard\NecoResultCardInterface;
use App\Services\MobileAirtimeNg\ResultCardTraits;

class NecoResultCard implements NecoResultCardInterface
{

    use ResultCardTraits;

    public function buyResultCard(int $user_id = 0): array
    {
        //assign trans_ref
        $trans_ref = uniqid($user_id . "_");

        $process = $this->NecoResultCard($trans_ref);

        if (!empty($process['success'])) {
            $process['trans_ref'] = $trans_ref;
        }

        return $process;
    }
}
