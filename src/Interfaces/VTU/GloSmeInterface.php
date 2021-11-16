<?php

namespace App\Interfaces\VTU;

interface GloSmeInterface
{
    /**
     * SME Data topup
     *
     * @param int $user_id
     * @param string $phone
     * @param int $amount
     * @return array
     */
    public function topUp(int $user_id = 0, string $phone, int $amount): array;

    /**
     * Get price list for direct topup. All networks
     * 
     * @return array
     */
    public function priceList(): array;
}
