<?php

namespace App\Services\MobileAirtimeNg;

use App\Helpers\ApiRequest;
use App\Traits\GeneralTrait;

trait MobileAirtimeNgTraits
{
    use GeneralTrait;
    /**
     * ApiRequest handle
     *
     * @var \App\Helpers\ApiRequest
     */
    private $req;

    /**
     * NETWORK CODES
     */
    private $NC__MTN = 15;
    private $NC__GLO = 6;
    private $NC__AIRTEL = 1;
    private $NC__NINEMOBILE = 2;

    /**
     * RESPONSES
     */
    private $RESPONSE__SUCCESSFUL = 100;
    private $RESPONSE__ACCOUNT_NOT_ACTIVE = 101;
    private $RESPONSE__INVALID_AMOUNT = 102;
    private $RESPONSE__INSUFFICIENT_BALANCE = 103;
    private $RESPONSE__INVALID_USER_ID = 104;
    private $RESPONSE__INVALID_LOG_IN = 105;
    private $RESPONSE__NO_NETWORK_SELECTED = 106;
    private $RESPONSE__INVALID_PHONE = 107;
    private $RESPONSE__OTHER_PLATFORM_ERROR = 108;
    private $RESPONSE__INVALID_DEVELOPER_ACCOUNT = 109;

    /**
     * Balance check URL.
     *
     * @var string
     */
    private $balance_url = "https://mobileairtimeng.com/httpapi/balance.php?userid=%s&pass=%s";

    /**
     * Check transation status URL.
     *
     * @var string
     */
    private $check_trans_url = "https://mobileairtimeng.com/httpapi/status?userid=%s&pass=%s&transid=%s";

    public function __construct()
    {
        $this->req = new ApiRequest();
    }

    /**
     * Send the actual request to the external API
     *
     * @param string $url
     * @return array
     */
    private function makeRequest(string $url): array
    {

        $req = (object) $this->req->get($url);

        if (!empty($req->code)) {
            switch ($req->code) {
                case $this->RESPONSE__SUCCESSFUL: {
                        $return['success'] = true;
                        $return['body'] = $req;
                        break;
                    }
                case $this->RESPONSE__ACCOUNT_NOT_ACTIVE:
                case $this->RESPONSE__INVALID_AMOUNT:
                case $this->RESPONSE__INSUFFICIENT_BALANCE:
                case $this->RESPONSE__INVALID_USER_ID:
                case $this->RESPONSE__INVALID_LOG_IN:
                case $this->RESPONSE__NO_NETWORK_SELECTED:
                case $this->RESPONSE__INVALID_PHONE:
                case $this->RESPONSE__OTHER_PLATFORM_ERROR:
                case $this->RESPONSE__INVALID_DEVELOPER_ACCOUNT: {
                        $return['success'] = false;
                        $return['message'] = $req->message;
                        break;
                    }
                default: {
                        $return['success'] = false;
                        $return['message'] = "A fatal error occured. Please contact support.";
                        break;
                    }
            }
        } else {
            $return['success'] = false;
            $return['message'] = !empty($req->message) ? $req->message : "";
            $req->code = "11111";
        }


        $return['code'] = $req->code;
        $return['platform_id'] = $this->platform_id;

        return $return;
    }

    /**
     * Check available account balance
     *
     * @return array
     */
    private function checkBalance(): array
    {
        $url = sprintf(
            $this->balance_url,
            $_ENV['MOBILEAIRTIMENG_LOGIN'],
            $_ENV['MOBILEAIRTIMENG_PASSWORD']
        );

        //send request 
        try {
            return ['success' => true, 'balance' => (string)$this->req->getUrl($url)];
        } catch (\Exception $e) {
            return ['success' => false];
        }
    }

    /**
     * Check status of transaction here
     *
     * @param string $trans_ref
     * @return array
     */
    public function transStatus(string $trans_ref): array
    {
        $url = sprintf(
            $this->check_trans_url,
            $_ENV['MOBILEAIRTIMENG_LOGIN'],
            $_ENV['MOBILEAIRTIMENG_PASSWORD'],
            $trans_ref
        );

        //send request 
        return $this->makeRequest($url);
    }
}
