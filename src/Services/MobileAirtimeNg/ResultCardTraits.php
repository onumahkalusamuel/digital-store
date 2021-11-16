<?php

namespace App\Services\MobileAirtimeNg;

trait ResultCardTraits
{

    use MobileAirtimeNgTraits;

    private $weac_result_card_url = "https://mobileairtimeng.com/httpapi/waecdirect?userid=%s&pass=%s&jsn=json&user_ref=%s";

    private $neco_result_card_url = "https://mobileairtimeng.com/httpapi/neco?userid=%s&pass=%s&jsn=json&user_ref=%s";

    /**
     * WaecResultCard
     *
     * @param string $trans_ref
     * @return array
     */
    private function WaecResultCard(string $trans_ref): array
    {
        $url = sprintf(
            $this->weac_result_card_url,
            $_ENV['MOBILEAIRTIMENG_LOGIN'],
            $_ENV['MOBILEAIRTIMENG_PASSWORD'],
            $trans_ref
        );
        //send request 
        return $this->makeRequest($url);
    }

    /**
     * NecoResultCard
     *
     * @param string $trans_ref
     * @return array
     */
    private function NecoResultCard(string $trans_ref): array
    {
        $url = sprintf(
            $this->neco_result_card_url,
            $_ENV['MOBILEAIRTIMENG_LOGIN'],
            $_ENV['MOBILEAIRTIMENG_PASSWORD'],
            $trans_ref
        );
        //send request 
        return $this->makeRequest($url);
    }
}
