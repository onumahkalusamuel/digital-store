<?php

namespace App\Services\MobileAirtimeNg\VTU;

use App\Interfaces\VTU\AirtelSmeInterface;
use App\Services\MobileAirtimeNg\VTUTraits;

class AirtelSme implements AirtelSmeInterface
{
    use VTUTraits;

    public function topUp(int $user_id = 0, string $phone, int $amount): array
    {
        //verify network code
        $network = $this->getNetworkCode($phone);
        if ($network !== $this->NC__AIRTEL) {
            return [
                'success' => false,
                'message' => "Number not an Airtel number."
            ];
        }

        //assign trans_ref
        $trans_ref = uniqid($user_id . "_");

        // $this->AirtelSmeData($network, $phone, $amount, $trans_ref);
    }

    public function priceList(): array
    {
        return (array) $this->getSMEPrices('airtel');
    }
}
