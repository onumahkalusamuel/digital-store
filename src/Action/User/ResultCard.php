<?php

namespace App\Action\User;

use App\Helpers\SendMail;
use App\Interfaces\ResultCard\NabtebResultCardInterface;
use App\Interfaces\ResultCard\NecoResultCardInterface;
use App\Interfaces\ResultCard\WaecResultCardInterface;
use App\Repositories\SettingsRepository;
use App\Repositories\TrailLogRepository;
use App\Repositories\UsersRepository;
use App\Traits\GeneralTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;
use Symfony\Component\HttpFoundation\Session\Session;
use Smarty as View;

final class ResultCard
{

    use GeneralTrait;

    protected $user;
    protected $session;
    protected $trailLog;
    protected $settings;
    protected $view;
    protected $sendMail;
    protected $waec;
    protected $neco;
    protected $nabteb;

    public function __construct(
        UsersRepository $user,
        Session $session,
        View $view,
        SendMail $sendMail,
        SettingsRepository $settings,
        TrailLogRepository $trailLog,
        WaecResultCardInterface $waec,
        NecoResultCardInterface $neco,
        NabtebResultCardInterface $nabteb
    ) {
        $this->user = $user;
        $this->session = $session;
        $this->view = $view;
        $this->settings = $settings;
        $this->trailLog = $trailLog;
        $this->sendMail = $sendMail;
        $this->waec = $waec;
        $this->neco = $neco;
        $this->nabteb = $nabteb;
    }

    public function init(
        ServerRequestInterface $request,
        ResponseInterface $response,
        $args
    ): ResponseInterface {

        $examination = $args['examination'];

        $resultCardPriceTitle = "price__{$examination}_result_card";
        $amount = $this->settings->$resultCardPriceTitle;

        $this->view->assign('examination', $examination);
        $this->view->assign('amount', $amount);

        $this->view->display('user/result-card.tpl');

        return $response;
    }

    public function confirm(
        ServerRequestInterface $request,
        ResponseInterface $response,
        $args
    ): ResponseInterface {

        $data = (array) $request->getParsedBody();
        $examination = $args['examination'];
        $responseBody = [];
        $message = "";

        $resultCardPriceTitle = "price__{$examination}_result_card";
        $amount = $this->settings->$resultCardPriceTitle;

        $id = $this->session->get('id');

        if (empty($message) && empty($data['confirmation'])) {
            $message = "Tick the checkbox to confirm action.";
        }

        $user = $this->user->readSingle(['id' => $id]);

        if (empty($message) && empty($user->id)) {
            $message = "User not found.";
        }

        // check if available balance can cover the plan
        if (empty($message) && $user->balance < $amount) {
            $message = "Balance insufficient to process request.";
        }

        // process request 
        if (empty($message)) {
            $buyResultCard = $this->$examination->buyResultCard($id);
        }

        // if not successful, drop error message 
        if (empty($message) && empty($buyResultCard['success'])) {
            if (empty($buyResultCard['code'])) $message = $buyResultCard['message'];
            else {
                $trackingId = uniqid();
                $this->logMessage('result-card-error', $trackingId . " :: " . json_encode($buyResultCard));
                $message = "A technical error occured. TrackingID: {$trackingId}. Forward code to support.";
            }
        }

        // if successful, deduct money from account, and give loyalty bonus
        if (empty($message)) {
            $balance_before = $user->balance;
            $balance_after = $user->balance - $amount;
            $loyalty_points = round($amount * $_ENV['LOYALTY_POINTS_RATE']);
            $this->user->update([
                'id' => $id,
                'data' => [
                    'balance' => $balance_after,
                    'loyalty_points' => $loyalty_points
                ]
            ]);
        }

        // log it in transactions
        if (empty($message)) {
            $this->trailLog->create([
                'data' => [
                    'user_id' => $id,
                    'log_type' => 'result-card',
                    'destination' => $user->email,
                    'amount' => $amount,
                    'description' => "Result Card Purchase - {$examination}",
                    'trans_ref' => $buyResultCard['trans_ref'],
                    'balance_before' => $balance_before,
                    'balance_after' => $balance_after,
                    'loyalty_points' => $loyalty_points,
                    'platform_id' => $buyResultCard['platform_id'],
                    'details' => json_encode($buyResultCard)
                ]
            ]);

            unset($buyResultCard['platform_id']);
            unset($buyResultCard['code']);
            unset($buyResultCard['message']);

            // send card details to mail
            $send = $this->sendMail->sendResultCardPurchase(
                $buyResultCard,
                $examination,
                $user->email
            );

            // notify the user of this transaction
            $this->sendMail->sendTransactionLog([
                'transaction' => "Result Card - {$examination}",
                'trans_ref' => $buyResultCard['trans_ref'],
                'amount' => $amount,
                'destination' => $user->email,
                'balance_before' => $balance_before,
                'balance_after' => $balance_after,
                'loyalty_points' => $loyalty_points,
            ], $user->email);
        }

        // prepare response body
        if (empty($message)) {
            $responseBody['success'] = true;
            $responseBody['message'] = "";
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            $responseBody['redirect'] = $routeParser->urlFor("result-card-preview", [
                'examination' => $examination,
                'trans_ref' => $buyResultCard['trans_ref']
            ]);
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
