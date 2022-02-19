<?php

namespace App\Services\MobileAirtimeNg\ResultCard;

use App\Interfaces\ResultCard\NabtebResultCardInterface;
use App\Responses\ResultCard\ResultCardResponse;
use App\Services\MobileAirtimeNg\ResultCardTraits;

class NabtebResultCard implements NabtebResultCardInterface
{

    use ResultCardTraits;

    public function buyResultCard(int $user_id = 0): ResultCardResponse
    {
        return new ResultCardResponse(false, 'not implemented yet');
    }
}
