<?php

namespace App\Action\Api;

use App\Helpers\SendMail;
use App\Interfaces\Payment\QuickBuyPaymentInterface;
use App\Interfaces\ResultCard\NabtebResultCardInterface;
use App\Interfaces\ResultCard\NecoResultCardInterface;
use App\Interfaces\ResultCard\WaecResultCardInterface;
use App\Interfaces\VTU\AirtelAirtimeInterface;
use App\Interfaces\VTU\AirtelDataInterface;
use App\Interfaces\VTU\GloAirtimeInterface;
use App\Interfaces\VTU\GloDataInterface;
use App\Interfaces\VTU\MtnAirtimeInterface;
use App\Interfaces\VTU\MtnDataInterface;
use App\Interfaces\VTU\MtnSmeInterface;
use App\Interfaces\VTU\NineMobileAirtimeInterface;
use App\Interfaces\VTU\NineMobileDataInterface;
use App\Objects\PaymentLinkDetailsObject;
use App\Repositories\PaymentsRepository;
use App\Repositories\ProductsRepository;
use App\Repositories\QuickBuyRepository;
use App\Repositories\TrailLogRepository;
use App\Responses\ResultCard\ResultCardResponse;
use App\Responses\VTU\VTUResponse;
use App\Traits\GeneralTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;
use Symfony\Component\HttpFoundation\Session\Session;
use Smarty as View;

final class QuickBuy
{

    use GeneralTrait;

    private $session;
    private $trailLog;
    private $view;
    private $sendMail;
    private $airtelAirtime;
    private $mtnAirtime;
    private $gloAirtime;
    private $ninemobileAirtime;
    private $airtelData;
    private $mtnData;
    private $gloData;
    private $ninemobileData;
    private $mtnSme;
    private $waec;
    private $neco;
    private $nabteb;
    private $quickBuy;
    private $quickBuyPayment;
    private $payments;
    private $products;

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
        WaecResultCardInterface $waec,
        NecoResultCardInterface $neco,
        NabtebResultCardInterface $nabteb,
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
        $this->waec = $waec;
        $this->neco = $neco;
        $this->nabteb = $nabteb;
        $this->quickBuy = $quickBuy;
        $this->quickBuyPayment = $quickBuyPayment;
        $this->payments = $payments;
        $this->products = $products;
    }

    public function init(
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
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            $redirect_url = $routeParser->urlFor("payment-redirect", [
                'transaction_id' => $transactionId
            ]);

            $quickBuyPayment = $this->quickBuyPayment->generatePaymentLink(
                $transactionId,
                $amount,
                new PaymentLinkDetailsObject('Guest', $phone, $email, $redirect_url)
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

    public function processTransaction($transactionId): array
    {
        $message = '';
        $vtuResponse = new VTUResponse();
        $resultCardResponse = new ResultCardResponse();
        $response = [
            'success' => false,
            'message' => ''
        ];

        // fetch the transaction
        $trans = $this->quickBuy->find([
            'params' => ['transaction_id' => $transactionId]
        ]);

        if (empty($trans)) {
            $message = "Transaction not found. Please contact support";
        }

        if (empty($message) && $trans->transaction_status != "pending") {
            $message = "Transaction already processed. Please contact support if you need assistance.";
        }

        if (empty($message)) {
            $details = json_decode($trans->details, true);
        }

        // process sme-data
        if (empty($message)) {
            if ($details['product'] == "sme-data") {
                $topUp = $vtuResponse;
                // get the actual amount for the selected plan
                if ($details['network'] == "mtn") {
                    $topUp = $this->mtnSme->topUp(0, $details['phone'], $details['units']);
                }
            }
        }

        if (empty($message)) {
            if ($details['product'] == "vtu-data") {
                $topUp = $vtuResponse;
                if ($details['network'] == "mtn") {
                    $topUp = $this->mtnData->topUp(0, $details['phone'], $details['amount']);
                }
                if ($details['network'] == "airtel") {
                    $topUp = $this->airtelData->topUp(0, $details['phone'], $details['amount']);
                }
                if ($details['network'] == "glo") {
                    $topUp = $this->gloData->topUp(0, $details['phone'], $details['amount']);
                }
                if ($details['network'] == "ninemobile") {
                    $topUp = $this->ninemobileData->topUp(0, $details['phone'], $details['amount']);
                }
            }
        }

        if (empty($message)) {
            if ($details['product'] == "vtu-airtime") {
                $topUp = $vtuResponse;
                if ($details['network'] == "mtn") {
                    $topUp = $this->mtnAirtime->topUp(0, $details['phone'], $details['amount']);
                }
                if ($details['network'] == "airtel") {
                    $topUp = $this->airtelAirtime->topUp(0, $details['phone'], $details['amount']);
                }
                if ($details['network'] == "glo") {
                    $topUp = $this->gloAirtime->topUp(0, $details['phone'], $details['amount']);
                }
                if ($details['network'] == "ninemobile") {
                    $topUp = $this->ninemobileAirtime->topUp(0, $details['phone'], $details['amount']);
                }
            }
        }

        // conclude the above transtions. to start result card
        if (empty($topUp->success)) {
            $message = $topUp->message ?? "Top up processing. Please try again.";
        }
        if (empty($message)) {
            $details['trans_ref'] = $topUp->trans_ref;
            $details['platform_id'] = $topUp->platform_id;
        }

        // continue to the scratch cards
        if (empty($message)) {
            if ($details['product'] == "result-card") {
                $resultCard = $resultCardResponse;
                $purchasedCards = [];
                for ($i = 0; $i < $details['quantity']; $i++) {

                    if ($details['examination'] == "waec") {
                        $resultCard = $this->waec->buyResultCard();
                    }
                    if ($details['examination'] == "neco") {
                        $resultCard = $this->neco->buyResultCard();
                    }
                    if ($details['examination'] == "nabteb") {
                        $resultCard = $this->nabteb->buyResultCard();
                    }

                    if (empty($resultCard->success)) {
                        $message = $resultCard->message;
                        break;
                    }
                    $purchasedCards[] = $resultCard;
                }

                if (!empty($purchasedCards)) {
                    // save it all in the details
                    $details['purchased_cards'] = $purchasedCards;

                    $this->quickBuy->update([
                        'id' => $trans->id,
                        'details' => [
                            'details' => json_encode($details),
                        ]
                    ]);


                    // prepare and notify user
                    foreach ($purchasedCards as $pc) {
                        $this->sendMail->sendResultCardPurchase(
                            $pc,
                            $details['examination'],
                            $details['email']
                        );
                    }
                }
            }
        }

        // update quickbuy record
        if (empty($message)) {
            $this->quickBuy->update([
                'id' => $trans->id,
                'details' => [
                    'details' => json_encode($details),
                    'payment_status' => 'completed',
                    'transaction_status' => 'completed'
                ]
            ]);
        }

        if (empty($message)) {
            $response['message'] = "Transaction processed successfully.";
            $response['success'] = true;
        } else {
            $response['message'] = $message;
        }

        return $response;
    }
}
