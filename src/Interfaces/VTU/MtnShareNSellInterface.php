<?php

namespace App\Interfaces\VTU;

interface MtnShareNSellInterface
{
    /**
     * Share and Sell airtime for mtn
     *
     * @param int $user_id
     * @param string $phone
     * @param int $amount
     * @return array
     */
    public function topUp(int $user_id = 0, string $phone, int $amount): array;
}
