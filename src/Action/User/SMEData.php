<?php

namespace App\Action\User;

use App\Helpers\SendMail;
use App\Interfaces\VTU\AirtelSmeInterface;
use App\Interfaces\VTU\GloSmeInterface;
use App\Interfaces\VTU\MtnSmeInterface;
use App\Interfaces\VTU\NineMobileSmeInterface;
use App\Repositories\TrailLogRepository;
use App\Repositories\UsersRepository;
use App\Traits\GeneralTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;
use Symfony\Component\HttpFoundation\Session\Session;
use Smarty as View;

final class SMEData
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
        AirtelSmeInterface $airtel,
        MtnSmeInterface $mtn,
        GloSmeInterface $glo,
        NineMobileSmeInterface $ninemobile
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

        $priceList = $this->$network->priceList($network);

        $this->view->assign('priceList', $priceList);
        $this->view->assign('network', $network);

        $this->view->display('user/sme-data.tpl');

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
        $priceList = $this->$network->priceList($network);
        $currentBundle = $priceList[$data['bundle']];

        if (empty($currentBundle)) {
            $message = "Bundle not available or invalid.";
        }

        $user = $this->user->readSingle(['id' => $id]);

        if (empty($message) && empty($user->id)) {
            $message = "User not found.";
        }

        // check if available balance can cover the plan
        if (empty($message) && (empty($user->balance) || $user->balance < $currentBundle['amount'])) {
            $message = "Balance insufficient to process request.";
        }

        // process request 
        if (empty($message)) {
            $smedata = $this->$network->topUp($id, $data['phone'], $currentBundle['units']);
        }

        // if not successful, drop error message 
        if (empty($message) && empty($smedata['success'])) {
            if (empty($smedata['code'])) $message = $smedata['message'];
            else {
                $trackingId = uniqid();
                $this->logMessage('sme-data-error', $trackingId . " :: " . json_encode($smedata));
                $message = "A technical error occured. TrackingID: {$trackingId}. Forward code to support.";
            }
        }

        // if successful, deduct money from account, and give loyalty bonus
        if (empty($message)) {
            $balance_before = $user->balance;
            $balance_after = $user->balance - $currentBundle['amount'];
            $loyalty_points = round($currentBundle['amount'] * $_ENV['LOYALTY_POINTS_RATE']);
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
                    'log_type' => 'sme-data',
                    'destination' => $data['phone'],
                    'amount' => $currentBundle['amount'],
                    'description' => "SME Data - {$network}",
                    'trans_ref' => $smedata['trans_ref'],
                    'balance_before' => $balance_before,
                    'balance_after' => $balance_after,
                    'loyalty_points' => $loyalty_points,
                    'platform_id' => $smedata['platform_id']
                ]
            ]);

            // notify the user of this transaction
            $this->sendMail->sendTransactionLog([
                'transaction' => "SME Data - {$network}",
                'service_provider' => $network,
                'trans_ref' => $smedata['trans_ref'],
                'amount' => $currentBundle['amount'],
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

    public function priceList(
        ServerRequestInterface $request,
        ResponseInterface $response,
        $args
    ): ResponseInterface {

        $network = $args['network'];

        $priceList = $this->$network->priceList($network);

        $response->getBody()->write(json_encode($priceList));

        return $response;
    }
}
