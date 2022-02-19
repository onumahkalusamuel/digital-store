<?php

namespace App\Interfaces\VTU;

use App\Responses\VTU\VTUResponse;

interface MtnShareNSellInterface
{
    /**
     * Share and Sell airtime for mtn
     *
     * @param int $user_id
     * @param string $phone
     * @param int $amount
     * @return \App\Responses\VTU\VTUResponse
     */
    public function topUp(int $user_id = 0, string $phone, int $amount): VTUResponse;
}
