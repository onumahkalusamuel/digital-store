<?php

namespace App\Services\MobileAirtimeNg\VTU;

use App\Interfaces\VTU\AirtelDataInterface;
use App\Services\MobileAirtimeNg\VTUTraits;

class AirtelData implements AirtelDataInterface
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

        $subscribe = $this->dataTopUp($network, $phone, $amount, $trans_ref);

        if (!empty($subscribe['success'])) {
            $subscribe['trans_ref'] = $trans_ref;
        }

        return $subscribe;
    }

    public function priceList(): array
    {
        return (array) $this->getDataTopUpPriceList('airtel');
    }
}
