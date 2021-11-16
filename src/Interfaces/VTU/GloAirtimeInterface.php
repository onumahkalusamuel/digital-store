<?php

namespace App\Interfaces\VTU;

interface GloAirtimeInterface
{
    /**
     * Direct airtime topup. All networks
     *
     * @param int $user_id
     * @param string $phone
     * @param int $amount
     * @return array
     */
    public function topUp(int $user_id = 0, string $phone, int $amount): array;
}
