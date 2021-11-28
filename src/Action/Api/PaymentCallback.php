<?php

namespace App\Action\Api;

use App\Helpers\SendMail;
use App\Interfaces\Payment\QuickBuyPaymentInterface;
use App\Interfaces\VTU\AirtelAirtimeInterface;
use App\Interfaces\VTU\AirtelDataInterface;
use App\Interfaces\VTU\GloAirtimeInterface;
use App\Interfaces\VTU\GloDataInterface;
use App\Interfaces\VTU\MtnAirtimeInterface;
use App\Interfaces\VTU\MtnDataInterface;
use App\Interfaces\VTU\MtnSmeInterface;
use App\Interfaces\VTU\NineMobileAirtimeInterface;
use App\Interfaces\VTU\NineMobileDataInterface;
use App\Repositories\PaymentsRepository;
use App\Repositories\ProductsRepository;
use App\Repositories\QuickBuyRepository;
use App\Repositories\TrailLogRepository;
use App\Traits\GeneralTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;
use Symfony\Component\HttpFoundation\Session\Session;
use Smarty as View;

final class PaymentCallback
{

    use GeneralTrait;

    protected $session;
    protected $trailLog;
    protected $view;
    protected $sendMail;
    protected $airtelAirtime;
    protected $mtnAirtime;
    protected $gloAirtime;
    protected $ninemobileAirtime;
    protected $airtelData;
    protected $mtnData;
    protected $gloData;
    protected $ninemobileData;
    protected $mtnSme;
    protected $quickBuy;
    protected $quickBuyPayment;
    protected $payments;
    protected $products;

    public function __construct(
        Session $session,
        View $view,
        TrailLogRepository $trailLog,
        SendMail $sendMail,
        AirtelAirtimeInterface $airtelAirtime,
        MtnAirtimeInterface $mtnAirtime,
        GloAirtimeInterface $gloAirtime,
        NineMobileAirtimeInterface $ninemobileAirtime,
        AirtelDataInterface $airtelData,
        MtnDataInterface $mtnData,
        GloDataInterface $gloData,
        NineMobileDataInterface $ninemobileData,
        MtnSmeInterface $mtnSme,
        QuickBuyRepository $quickBuy,
        QuickBuyPaymentInterface $quickBuyPayment,
        PaymentsRepository $payments,
        ProductsRepository $products
    ) {
        $this->session = $session;
        $this->view = $view;
        $this->trailLog = $trailLog;
        $this->sendMail = $sendMail;
        $this->airtelAirtime = $airtelAirtime;
        $this->mtnAirtime = $mtnAirtime;
        $this->gloAirtime = $gloAirtime;
        $this->ninemobileAirtime = $ninemobileAirtime;
        $this->airtelData = $airtelData;
        $this->mtnData = $mtnData;
        $this->gloData = $gloData;
        $this->ninemobileData = $ninemobileData;
        $this->mtnSme = $mtnSme;
        $this->quickBuy = $quickBuy;
        $this->quickBuyPayment = $quickBuyPayment;
        $this->payments = $payments;
        $this->products = $products;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        $args
    ): ResponseInterface {

        $message = "";
        $paymentId = "";
        $responseBody = [];
        $data = $request->getParsedBody();
        $product = $data['product'];
        $network = $data['network'];
        $examination = $data['examination'];
        $amount = $data['amount'];
        $plan = $data['plan'];
        $email = $data['email'];
        $phone = $data['phone'];
        $quantity = $data['quantity'];
        $transactionId = strtoupper(uniqid('GST-'));
        $units = [];

        if ($product == "sme-data") {
            // get the actual amount for the selected plan
            if ($network == "mtn") $priceList = $this->mtnSme->priceList();
            foreach ($priceList as $pl) {
                if ($pl['amount'] == $plan) {
                    $units = $pl['units'];
                    break;
                }
            }
            if (!empty($units)) {
                $data['units'] = $units;
                $data['amount'] = $amount;
            } else {
                $message = "Unable to find a matching plan";
            }

            $service_provider = $network;
            $amount = $plan;
            $data['amount'] = $amount = $plan;
        } elseif ($product == "vtu-data") {
            $service_provider = $network;
            $data['amount'] = $amount = $plan;
        } elseif ($product == "vtu-airtime") {
            $service_provider = $network;
        } elseif ($product == "result-card") {

            // check qty
            if (empty($message) && empty($quantity)) {
                $message = "You must order at least 1 quantity";
            }

            // get the price of examination card
            if (empty($message)) {
                $price = $this->products->find([
                    'params' => [
                        'category' => 'result-card',
                        'type' => $examination
                    ],
                    'select' => ['id', 'price']
                ]);

                if (empty($price->id)) {
                    $message = "Result Card not found.";
                }
            }

            if (empty($message)) {
                $data['amount'] = $amount = $quantity * $price->price;
                $service_provider = $examination;
            }
        }

        // send amount to payment link generator
        if (empty($message)) {
            $quickBuyPayment = $this->quickBuyPayment->generatePaymentLink(
                $transactionId,
                $amount,
                ['name' => 'Guest', 'phone' => $phone, 'email' => $email]
            );
        }

        //check if link came through
        if (empty($message) && empty($quickBuyPayment['success'])) {
            $message = $quickBuyPayment['message'] ?? "Unable to process your request at the moment.";
        }

        if (empty($message) && !empty($quickBuyPayment['payment_link'])) {
            $responseBody['payment_link'] = $quickBuyPayment['payment_link'];
        }

        // save to payments page
        if (empty($message)) {
            $paymentId = $this->payments->create([
                'data' => [
                    'type' => 'quickbuy',
                    'service_id' => $quickBuyPayment['platform_id'],
                    'transaction_id' => $transactionId,
                    'amount' => $amount,
                    'details' => json_encode($quickBuyPayment)
                ]
            ]);

            if (empty($paymentId)) {
                $message = "Invalid generated payment link. Please try again later.";
            }
        }

        // save to quickbuy records
        if (empty($message)) {
            $save = $this->quickBuy->create([
                'data' => [
                    'product' => $product,
                    'service_provider' => $service_provider,
                    'transaction_id' => $transactionId,
                    'details' => json_encode($data),
                    'payment_id' => $paymentId

                ]
            ]);

            if (empty($save)) {
                $message = "Unable to save the transaction at the moment. Please try again later.";
            }
        }

        // return the payment link to user for display and wait for payment
        if (empty($message)) {
            $responseBody['success'] = true;
            $responseBody['message'] = "You'll be redirected to payment page shortly.";
        } else {
            $responseBody['success'] = false;
            $responseBody['message'] = $message;
        }

        $response->getBody()->write(json_encode($responseBody));

        return $response;
    }
}
