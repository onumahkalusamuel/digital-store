<?php

namespace App\Services\MobileAirtimeNg\ResultCard;

use App\Interfaces\ResultCard\NabtebResultCardInterface;
use App\Services\MobileAirtimeNg\ResultCardTraits;

class NabtebResultCard implements NabtebResultCardInterface
{

    use ResultCardTraits;

    public function buyResultCard(int $user_id = 0): array
    {
        //assign trans_ref

        return ['success' => false, 'message' => 'not implemented yet'];
    }
}
