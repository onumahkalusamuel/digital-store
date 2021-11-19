<?php

namespace App\Action\User;

use App\Interfaces\VTU\AirtelDataInterface;
use App\Interfaces\VTU\GloDataInterface;
use App\Interfaces\VTU\MtnDataInterface;
use App\Interfaces\VTU\NineMobileDataInterface;
use App\Repositories\TrailLogRepository;
use App\Repositories\UsersRepository;
use App\Traits\GeneralTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;
use Symfony\Component\HttpFoundation\Session\Session;
use Smarty as View;

final class VTUData
{

    use GeneralTrait;

    protected $user;
    protected $session;
    protected $trailLog;
    protected $view;
    protected $airtel;
    protected $mtn;
    protected $glo;
    protected $ninemobile;

    public function __construct(
        UsersRepository $user,
        Session $session,
        View $view,
        TrailLogRepository $trailLog,
        AirtelDataInterface $airtel,
        MtnDataInterface $mtn,
        GloDataInterface $glo,
        NineMobileDataInterface $ninemobile
    ) {
        $this->user = $user;
        $this->session = $session;
        $this->view = $view;
        $this->trailLog = $trailLog;
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
        $priceList = $this->$network->priceList();

        $this->view->assign('prefixes', $prefixes);
        $this->view->assign('priceList', $priceList);
        $this->view->assign('network', $network);

        $this->view->display('user/vtu-data.tpl');

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
        // check if available balance can cover the plan
        if (empty($message) && $user->balance < $data['bundle']) {
            $message = "Balance insufficient to process request.";
        }

        // process request 
        if (empty($message)) {
            $vtudata = $this->$network->topUp($id, $data['phone'], $data['bundle']);
        }

        // if not successful, drop error message 
        if (empty($message) && empty($vtudata['success'])) {
            if (empty($vtudata['code'])) $message = $vtudata['message'];
            else {
                $trackingId = uniqid();
                $this->logMessage('vtu-data-error', $trackingId . " :: " . json_encode($vtudata));
                $message = "A technical error occured. TrackingID: {$trackingId}. Forward code to support.";
            }
        }

        // if successful, deduct money from account, and give loyalty bonus
        if (empty($message)) {
            $balance_before = $user->balance;
            $balance_after = $user->balance - $data['bundle'];
            $loyalty_points = round($data['bundle'] * $_ENV['LOYALTY_POINTS_RATE']);
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
                    'log_type' => 'vtu-data',
                    'destination' => $data['phone'],
                    'amount' => $data['bundle'],
                    'description' => "VTU Data - {$network}",
                    'trans_ref' => $vtudata['trans_ref'],
                    'balance_before' => $balance_before,
                    'balance_after' => $balance_after,
                    'loyalty_points' => $loyalty_points,
                    'platform_id' => $vtudata['platform_id']
                ]
            ]);

            // notify the user of this transaction
            $this->sendMail->sendTransactionLog([
                'transaction' => "SME Data - {$network}",
                'trans_ref' => $vtudata['trans_ref'],
                'amount' => $data['bundle'],
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
