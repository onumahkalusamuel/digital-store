<?php

namespace App\Interfaces\VTU;

use App\Responses\VTU\VTUResponse;

interface NineMobileDataInterface
{
    /**
     * Direct data topup. All networks
     *
     * @param int $user_id
     * @param string $phone
     * @param int $amount
     * @return \App\Responses\VTU\VTUResponse
     */
    public function topUp(int $user_id = 0, string $phone, int $amount): VTUResponse;

    /**
     * Get price list for direct topup. All networks
     * 
     * @return array
     */
    public function priceList(): array;
}
