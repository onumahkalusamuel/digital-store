<?php

namespace App\Interfaces\VTU;

use App\Responses\VTU\VTUResponse;

interface MtnSmeInterface
{
    /**
     * SME Data topup
     *
     * @param int $user_id
     * @param string $phone
     * @param int $datasize
     * @return \App\Responses\VTU\VTUResponse
     */
    public function topUp(int $user_id = 0, string $phone, int $datasize): VTUResponse;

    /**
     * Get price list for direct topup. All networks
     * 
     * @return array
     */
    public function priceList(): array;
}
