<?php

namespace App\Services\MobileAirtimeNg\VTU;

use App\Interfaces\VTU\NineMobileSmeInterface;
use App\Services\MobileAirtimeNg\VTUTraits;

class NineMobileSme implements NineMobileSmeInterface
{
    use VTUTraits;

    public function topUp(int $user_id = 0, string $phone, int $amount): array
    {
        //verify network code
        $network = $this->getNetworkCode($phone);
        if ($network !== $this->NC__NINEMOBILE) {
            return [
                'success' => false,
                'message' => "Number not an 9Mobile number."
            ];
        }

        //assign trans_ref
        $trans_ref = uniqid($user_id . "_");

        // $this->MTNSMEData($network, $phone, $amount, $trans_ref);
    }

    public function priceList(): array
    {
        return (array) $this->getSMEPrices('ninemobile');
    }
}
