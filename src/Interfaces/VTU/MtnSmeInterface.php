<?php

namespace App\Interfaces\VTU;

interface MtnSmeInterface
{
    /**
     * SME Data topup
     *
     * @param int $user_id
     * @param string $phone
     * @param int $datasize
     * @return array
     */
    public function topUp(int $user_id = 0, string $phone, int $datasize): array;

    /**
     * Get price list for direct topup. All networks
     * 
     * @return array
     */
    public function priceList(): array;
}
