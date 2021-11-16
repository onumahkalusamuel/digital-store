<?php

namespace App\Services\MobileAirtimeNg;

use App\Traits\SMEPricesTrait;

trait VTUTraits
{
    use SMEPricesTrait;
    use MobileAirtimeNgTraits;

    /**
     * Airtime TopUp URL. For all networks
     *
     * @var string
     */
    private $airtime_topup_url = "https://mobileairtimeng.com/httpapi/?userid=%s&pass=%s&network=%s&phone=%s&amt=%s&user_ref=%s&jsn=json";

    /**
     * MTN ShareNSell URL. For MTN numbers only
     *
     * @var string
     */
    private $mtnsharensell_url = "https://mobileairtimeng.com/httpapi/msharesell?userid=%s&pass=%s&phone=%s&amt=%s&user_ref=%s&jsn=json";

    /**
     * MTN SME data URL
     *
     * @var string
     */
    private $mtnsmedata_url = "https://mobileairtimeng.com/httpapi/datashare?userid=%s&pass=%s&network=%s&phone=%s&datasize=%s&jsn=json&user_ref=%s";

    /**
     * Direct Data Topup URL. For all networks
     *
     * @var string
     */
    private $data_topup_url = "https://mobileairtimeng.com/httpapi/datatopup.php?userid=%s&pass=%s&network=%s&phone=%s&amt=%s&jsn=json";

    /**
     * MTN SME Data status check URL
     *
     * @var string
     */
    private $mtnsmedata_status_url = "https://mobileairtimeng.com/httpapi/datastatus?batch=%s";

    /**
     * Direct  Data Topup Price list URL
     * 
     * @var string
     */
    private $data_topup_pricelist_url = "https://mobileairtimeng.com/httpapi/get-items?userid=%s&pass=%s&service=%s";

    /**
     * Retrieve network code for provided phone
     *
     * @param String $phone
     * @return int
     */
    private function getNetworkCode(String $phone): int
    {
        $phone = ltrim($phone, "+");
        $phone = ltrim($phone, "0");
        $phone = ltrim($phone, "234");
        $phone = "0" . $phone;

        $phonePrefix = substr($phone, 0, 4);

        //check for MTN
        $mtn = explode(",", $_ENV['MTN_NUMBER_PREFIX']);
        foreach ($mtn as $p) if ($phonePrefix == trim($p)) return $this->NC__MTN;

        //check for Airtel
        $airtel = explode(",", $_ENV['AIRTEL_NUMBER_PREFIX']);
        foreach ($airtel as $p) if ($phonePrefix == trim($p)) return $this->NC__AIRTEL;

        //check for Glo
        $glo = explode(",", $_ENV['GLO_NUMBER_PREFIX']);
        foreach ($glo as $p) if ($phonePrefix == trim($p)) return $this->NC__GLO;

        //check for 9mobile
        $ninemobile = explode(",", $_ENV['NINEMOBILE_NUMBER_PREFIX']);
        foreach ($ninemobile as $p) if ($phonePrefix == trim($p)) return $this->NC__NINEMOBILE;

        return 0;
    }

    /**
     * Get the price list for Direct Data TopUp
     *
     * @param string $service
     * @return array
     */
    private function getDataTopUpPriceList(string $service): array
    {
        $priceList = [];

        $url = sprintf(
            $this->data_topup_pricelist_url,
            $_ENV['MOBILEAIRTIMENG_LOGIN'],
            $_ENV['MOBILEAIRTIMENG_PASSWORD'],
            $service
        );

        //send request 
        try {
            $list = $this->req->get($url);
            if ($list->response == "OK")
                $priceList = $this->toArray($list->products);
        } catch (\Exception $e) {
        }

        return $priceList;
    }

    /**
     * VTU Airtime topup. All networks
     *
     * @param int $network
     * @param string $phone
     * @param int $amount
     * @param string $trans_ref
     * @return array
     */
    public function airtimeTopUp(int $network, string $phone, int $amount, string $trans_ref)
    {
        $url = sprintf(
            $this->airtime_topup_url,
            $_ENV['MOBILEAIRTIMENG_LOGIN'],
            $_ENV['MOBILEAIRTIMENG_PASSWORD'],
            $network,
            $phone,
            $amount,
            $trans_ref
        );

        //send request 
        return $this->makeRequest($url);
    }
    /**
     * Direct Data topup. All networks
     *
     * @param int $network
     * @param string $phone
     * @param int $amount
     * @param string $trans_ref
     * @return array
     */
    public function dataTopUp(int $network, string $phone, int $amount, string $trans_ref): array
    {
        $url = sprintf(
            $this->data_topup_url,
            $_ENV['MOBILEAIRTIMENG_LOGIN'],
            $_ENV['MOBILEAIRTIMENG_PASSWORD'],
            $network,
            $phone,
            $amount,
            $trans_ref
        );

        //send request 
        return $this->makeRequest($url);
    }

    /**
     * MTN SME Data. MTN only
     *
     * @param string $phone
     * @param int $datasize
     * @param string $trans_ref
     * @return array
     */
    private function MtnSMEData(string $phone, int $datasize, string $trans_ref): array
    {
        $url = sprintf(
            $this->mtnsmedata_url,
            $_ENV['MOBILEAIRTIMENG_LOGIN'],
            $_ENV['MOBILEAIRTIMENG_PASSWORD'],
            $this->NC__MTN,
            $phone,
            $datasize,
            $trans_ref
        );

        //send request 
        return $this->makeRequest($url);
    }

    /**
     * MTN ShareNSell
     *
     * @param string $phone
     * @param int $amount
     * @param string $trans_ref
     * @return array
     */
    private function MtnShareNSell(string $phone, int $amount, string $trans_ref): array
    {
        $url = sprintf(
            $this->mtnsharensell_url,
            $_ENV['MOBILEAIRTIMENG_LOGIN'],
            $_ENV['MOBILEAIRTIMENG_PASSWORD'],
            $phone,
            $amount,
            $trans_ref
        );
        //send request 
        return $this->makeRequest($url);
    }

    private function getSMEPrices(string $network): array
    {
        switch ($network) {
            case 'mtn':
                return $this->MtnSmePrices();
                break;
            case 'airtel':
                return $this->AirtelSmePrices();
                break;
            case 'glo':
                return $this->GloSmePrices();
                break;
            case 'ninemobile':
                return $this->NineMobileSmePrices();
                break;

            default:
                return [];
                break;
        }
    }
}
