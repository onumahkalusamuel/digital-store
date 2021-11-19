<?php

namespace App\Action\User;

use App\Helpers\SendMail;
use App\Interfaces\VTU\AirtelAirtimeInterface;
use App\Interfaces\VTU\GloAirtimeInterface;
use App\Interfaces\VTU\MtnAirtimeInterface;
use App\Interfaces\VTU\NineMobileAirtimeInterface;
use App\Repositories\TrailLogRepository;
use App\Repositories\UsersRepository;
use App\Traits\GeneralTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;
use Symfony\Component\HttpFoundation\Session\Session;
use Smarty as View;

final class SNSAirtime
{

    use GeneralTrait;

    protected $user;
    protected $session;
    protected $trailLog;
    protected $view;
    protected $sendMail;
    protected $airtel;
    protected $mtn;
    protected $glo;
    protected $ninemobile;

    public function __construct(
        UsersRepository $user,
        Session $session,
        View $view,
        TrailLogRepository $trailLog,
        SendMail $sendMail,
        AirtelAirtimeInterface $airtel,
        MtnAirtimeInterface $mtn,
        GloAirtimeInterface $glo,
        NineMobileAirtimeInterface $ninemobile
    ) {
        $this->user = $user;
        $this->session = $session;
        $this->view = $view;
        $this->trailLog = $trailLog;
        $this->sendMail = $sendMail;
        $this->airtel = $airtel;
        $this->mtn = $mtn;
        $this->glo = $glo;
        $this->ninemobile = $ninemobile;
    }

    public function init(
        ServerRequestInterface $request,
        ResponseInterface $response,
        $args
    ): ResponseInterface {

        $network = $args['network'];

        $prefixes = $this->getPrefixes($network);

        $this->view->assign('prefixes', $prefixes);
        $this->view->assign('network', $network);

        $this->view->display('user/sns-airtime.tpl');

        return $response;
    }

    public function confirm(
        ServerRequestInterface $request,
        ResponseInterface $response,
        $args
    ): ResponseInterface {

        $network = $args['network'];
        $data = (array) $request->getParsedBody();
        $responseBody = [];
        $message = "";
        $id = $this->session->get('id');

        $user = $this->user->readSingle(['id' => $id]);

        if (empty($message) && empty($user->id)) {
            $message = "User not found.";
        }

        if (empty($message) && empty($data['amount'])) {
            $message = "Amount cannot be empty.";
        }

        // check if available balance can cover the plan
        if (empty($message) && $user->balance < $data['amount']) {
            $message = "Balance insufficient to process request.";
        }

        // process request 
        if (empty($message)) {
            $snsairtime = $this->$network->topUp($id, $data['phone'], $data['amount']);
        }

        // if not successful, drop error message 
        if (empty($message) && empty($snsairtime['success'])) {
            if (empty($snsairtime['code'])) $message = $snsairtime['message'];
            else {
                $trackingId = uniqid();
                $this->logMessage('sns-airtime-error', $trackingId . " :: " . json_encode($snsairtime));
                $message = "A technical error occured. TrackingID: {$trackingId}. Forward code to support.";
            }
        }

        // if successful, deduct money from account, and give loyalty bonus
        if (empty($message)) {
            $balance_before = $user->balance;
            $balance_after = $user->balance - $data['amount'];
            $loyalty_points = round($data['amount'] * $_ENV['LOYALTY_POINTS_RATE']);
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
                    'log_type' => 'sns-airtime',
                    'destination' => $data['phone'],
                    'amount' => $data['amount'],
                    'description' => "Airtime Share -{$network}",
                    'trans_ref' => $snsairtime['trans_ref'],
                    'balance_before' => $balance_before,
                    'balance_after' => $balance_after,
                    'loyalty_points' => $loyalty_points,
                    'platform_id' => $snsairtime['platform_id']
                ]
            ]);

            // notify the user of this transaction
            $this->sendMail->sendTransactionLog([
                'transaction' => "Airtime Share - {$network}",
                'trans_ref' => $snsairtime['trans_ref'],
                'amount' => $data['amount'],
                'destination' => $data['phone'],
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
            $responseBody['redirect'] = $routeParser->urlFor("dashboard");
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
