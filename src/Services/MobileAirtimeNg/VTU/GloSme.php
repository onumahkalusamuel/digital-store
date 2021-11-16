<?php

namespace App\Services\MobileAirtimeNg\VTU;

use App\Interfaces\VTU\GloSmeInterface;
use App\Services\MobileAirtimeNg\VTUTraits;

class GloSme implements GloSmeInterface
{
    use VTUTraits;

    public function topUp(int $user_id = 0, string $phone, int $amount): array
    {
        //verify network code
        $network = $this->getNetworkCode($phone);
        if ($network !== $this->NC__GLO) {
            return [
                'success' => false,
                'message' => "Number not an Glo number."
            ];
        }

        //assign trans_ref
        $trans_ref = uniqid($user_id . "_");

        // $this->GloSMEData($network, $phone, $amount, $trans_ref);
    }

    public function priceList(): array
    {
        return (array) $this->getSMEPrices('glo');
    }
}
