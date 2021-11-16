<?php

namespace App\Services\MobileAirtimeNg\VTU;

use App\Interfaces\VTU\NineMobileAirtimeInterface;
use App\Services\MobileAirtimeNg\VTUTraits;

class NineMobileAirtime implements NineMobileAirtimeInterface
{

    use VTUTraits;

    public function topUp(int $user_id = 0, string $phone, int $amount): array
    {
        //verify network code
        $network = $this->getNetworkCode($phone);
        if ($network !== $this->NC__NINEMOBILE) {
            return [
                'success' => false,
                'message' => "Number not a 9Mobile number."
            ];
        }

        //assign trans_ref
        $trans_ref = uniqid($user_id . "_");

        $subscribe = $this->airtimeTopUp($network, $phone, $amount, $trans_ref);

        if (!empty($subscribe['success'])) {
            $subscribe['trans_ref'] = $trans_ref;
        }

        return $subscribe;
    }
}
