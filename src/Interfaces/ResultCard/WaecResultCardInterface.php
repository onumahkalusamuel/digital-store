<?php

namespace App\Interfaces\ResultCard;

use App\Responses\ResultCard\ResultCardResponse;

interface WaecResultCardInterface
{
    /**
     * Waec Result Card Interface
     *
     * @return \App\Responses\ResultCard\ResultCardResponse
     */
    public function buyResultCard(int $user_id = 0): ResultCardResponse;
}
