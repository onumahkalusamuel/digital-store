<?php

namespace App\Services\Flutterwave;

use App\Helpers\ApiRequest;
use App\Traits\GeneralTrait;

trait FlutterwaveTraits
{
    use GeneralTrait;
    /**
     * ApiRequest handle
     *
     * @var \App\Helpers\ApiRequest
     */
    private $req;

    public $platform_id = 'flutterwave';

    public $payment_endpoint = "https://api.flutterwave.com/v3/payments";

    public $verification_endpoint = "https://api.flutterwave.com/v3/transactions/%s/verify";

    public function __construct()
    {
        $this->req = new ApiRequest();
    }

    public function generatePaymentLink($reference, $amount, $details = array()): array
    {
        $response = [
            'success' => false,
            'message' => 'Unable to create payment link. Please try again later',
            'payment_link' => '',
            'platform_id' => $this->platform_id
        ];


        // send request to generate payment link
        $data = [
            'tx_ref' => $reference,
            'amount' => $amount,
            'payment_options' => 'account,card,ussd,banktransfer,qr',
            'redirect_url' => $details['redirect_url'],
            'customer' => [
                'email' => $details['email'],
                'phonenumber' => $details['phone'],
                'name' => $details['name']
            ],
            'customizations' => [
                'title' => $_ENV['FLUTTERWAVE_TITLE'],
                'description' => $_ENV['FLUTTERWAVE_DESCRIPTION'],
                'logo' => $_ENV['FLUTTERWAVE_LOGO']
            ]
        ];

        $headers = ['Authorization' => "Bearer " . $_ENV['FLUTTERWAVE_SECRET_KEY']];

        try {
            $post = $this->req->post($this->payment_endpoint, $data, $headers);
            if ($post->status == "success" && !empty($post->data->link)) {
                $response['success'] = true;
                $response['payment_link'] = $post->data->link;
            } else {
                $response['message'] = $post->message ?? $response['message'];
            }
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
        }
        return $response;
    }

    public function paymentVerification($reference): array
    {

        $response = [
            'success' => false,
            'message' => 'unable to process request at the moment.',
            'amount' => 0,
        ];

        $headers = [
            "Content-Type" => "application/json",
            "Authorization" => "Bearer " . $_ENV['FLUTTERWAVE_SECRET_KEY']
        ];
        try {
            $get = $this->req->get(
                sprintf($this->verification_endpoint, $reference),
                $headers
            );

            if ($get->status == "success" && $get->data->status == "successful") {
                $response['success'] = true;
                $response['amount'] = $get->data->amount;
                $response['message'] = "successful";
            }
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
        }
        return $response;
    }
}
