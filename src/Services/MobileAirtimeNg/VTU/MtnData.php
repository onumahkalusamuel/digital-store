<?php

namespace App\Services\MobileAirtimeNg\VTU;

use App\Interfaces\VTU\MtnDataInterface;
use App\Services\MobileAirtimeNg\VTUTraits;

class MtnData implements MtnDataInterface
{

    use VTUTraits;

    public function topUp(int $user_id = 0, string $phone, int $amount): array
    {
        //verify network code
        $network = $this->getNetworkCode($phone);
        if ($network !== $this->NC__MTN) {
            return [
                'success' => false,
                'message' => "Number not an Mtn number."
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
        return (array) $this->getDataTopUpPriceList('mtn');
    }
}
