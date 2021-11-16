<?php

namespace App\Action\User;

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

    public function __construct(
        UsersRepository $user,
        Session $session,
        View $view,
        TrailLogRepository $trailLog,
        PaymentsRepository $payments,
        SettingsRepository $settings
    ) {
        $this->user = $user;
        $this->session = $session;
        $this->view = $view;
        $this->trailLog = $trailLog;
        $this->payments = $payments;
        $this->settings = $settings;
    }

    public function init(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

        $payments = $this->payments->readPaging([]);

        $account_details = [
            'account_name' => $this->settings->payments__account_name,
            'account_number' => $this->settings->payments__account_number,
            'bank_name' => $this->settings->payments__bank_name
        ];

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

        $user = $this->user->readSingle(['id' => $id]);

        if (empty($message) && empty($user->id)) {
            $message = "User not found.";
        }

        $payment_link = "";
        // process request 
        // 

        // log it in transactions
        if (empty($message)) {
            $this->trailLog->create([
                'data' => [
                    'user_id' => $id,
                    'log_type' => 'wallet-funding',
                    'description' => "Wallet Funding"
                ]
            ]);

            // notify the user of this transaction
            $this->sendMail->sendTransactionLog([
                'transaction' => "Wallet Funding"
            ], $user->email);
        }

        // prepare response body
        if (empty($message)) {
            $responseBody['success'] = true;
            $responseBody['message'] = "";
            $responseBody['redirect'] = $payment_link;
        } else {
            $responseBody['success'] = false;
            $responseBody['message'] = !empty($message) ? $message : 'Unable to process request at the moment!';
        }

        // write body
        $response->getBody()->write(json_encode($responseBody));

        // return response
        return $response;
    }
}
