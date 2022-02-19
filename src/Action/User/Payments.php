<?php

namespace App\Action\User;

use App\Helpers\SendMail;
use App\Interfaces\Payment\AccountTopUpPaymentInterface;
use App\Objects\PaymentLinkDetailsObject;
use App\Repositories\PaymentsRepository;
use App\Repositories\SettingsRepository;
use App\Repositories\TrailLogRepository;
use App\Repositories\UsersRepository;
use App\Traits\GeneralTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;
use Symfony\Component\HttpFoundation\Session\Session;
use Smarty as View;

final class Payments
{

    use GeneralTrait;

    protected $user;
    protected $session;
    protected $trailLog;
    protected $view;
    protected $payments;
    protected $settings;
    protected $sendMail;
    protected $account;

    public function __construct(
        UsersRepository $user,
        Session $session,
        View $view,
        TrailLogRepository $trailLog,
        PaymentsRepository $payments,
        SettingsRepository $settings,
        SendMail $sendMail,
        AccountTopUpPaymentInterface $account
    ) {
        $this->user = $user;
        $this->session = $session;
        $this->view = $view;
        $this->trailLog = $trailLog;
        $this->payments = $payments;
        $this->settings = $settings;
        $this->sendMail = $sendMail;
        $this->account = $account;
    }

    public function init(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

        // process pagination

        $payments = $this->payments->readPaging([
            'params' => [
                'where' => ['type' => 'accounttopup']
            ],
            'filters' => [
                'order_by' => 'status',
                'order' => 'DESC'
            ]
        ]);

        $payments['data'] = $this->toArray($payments['data']);

        $account_details = [
            'account_name' => $this->settings->payments__account_name,
            'account_number' => $this->settings->payments__account_number,
            'bank_name' => $this->settings->payments__bank_name
        ];

        $this->toArray($payments['data']);

        foreach ($payments['data'] as $key => $p) {
            $date = strtotime($payments['data'][$key]['created_at']);
            $payments['data'][$key]['created_at'] = date("d-M-Y", $date);
            $payments['data'][$key]['payment_link'] = '';
            if ($p['status'] == "pending") {
                $payments['data'][$key]['payment_link'] = json_decode($p['details'])->payment_link;
            }
        }

        $this->view->assign('payments', $payments);
        $this->view->assign('account_details', $account_details);

        $this->view->display('user/payments.tpl');

        return $response;
    }

    public function initiatePayment(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

        $data = (array) $request->getParsedBody();
        $responseBody = [];
        $message = "";

        $id = $this->session->get('id');

        if (empty($message) && empty($data['amount'])) {
            $message = "Please enter a valid amount.";
        }

        $user = $this->user->readSingle(['id' => $id]);

        if (empty($message) && empty($user->id)) {
            $message = "User not found.";
        }

        if (empty($message)) {
            $reference = strtoupper(uniqid("{$id}-"));
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            $redirect_url = $routeParser->urlFor("payment-redirect", [
                'transaction_id' => $reference
            ]);
            $accountTopUp = $this->account->generatePaymentLink(
                $reference,
                $data['amount'],
                new PaymentLinkDetailsObject($user->fullname, $user->phone, $user->email, $redirect_url)
            );
        }

        //check if link came through
        if (empty($message) && empty($accountTopUp['success'])) {
            $message = $accountTopUp['message'] ?? "Unable to process your request at the moment.";
        }

        if (empty($message) && !empty($accountTopUp['payment_link'])) {
            $responseBody['payment_link'] = $accountTopUp['payment_link'];
        }

        // save to payments record
        if (empty($message)) {
            $paymentId = $this->payments->create([
                'data' => [
                    'type' => 'quickbuy',
                    'service_id' => $accountTopUp['platform_id'],
                    'transaction_id' => $reference,
                    'amount' => $data['amount'],
                    'details' => json_encode($accountTopUp)
                ]
            ]);

            if (empty($paymentId)) {
                $message = "Invalid generated payment link. Please try again later.";
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
