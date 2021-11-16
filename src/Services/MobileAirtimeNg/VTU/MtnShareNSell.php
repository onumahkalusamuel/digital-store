<?php

namespace App\Services\MobileAirtimeNg\VTU;

use App\Interfaces\VTU\MtnShareNSellInterface;
use App\Services\MobileAirtimeNg\VTUTraits;

class MtnShareNSell implements MtnShareNSellInterface
{

    use VTUTraits;

    public function topUp(int $user_id = 0, string $phone, int $amount): array
    {
        //verify network code
        $network = $this->getNetworkCode($phone);
        if ($network !== $this->NC__MTN) {
            return [
                'success' => false,
                'message' => "Number not an MTN number."
            ];
        }

        //assign trans_ref
        $trans_ref = uniqid($user_id . "_");

        $process = $this->MtnShareNSell($phone, $amount, $trans_ref);

        if (!empty($process['success'])) {
            $process['trans_ref'] = $trans_ref;
        }

        return $process;
    }
}
