<?php

namespace App\Action\User;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Smarty as View;

final class UserDashboardAction
{
    protected $user;
    protected $session;
    protected $deposits;
    protected $withdrawals;
    protected $referrals;
    protected $trailLog;
    protected $view;

    public function __construct(
        Session $session,
        View $view
    ) {
        $this->session = $session;
        $this->view = $view;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

        $ID = $this->session->get('ID');

        $data['user_name'] = "";
        $data['full_name'] = "";
        $data['registration_date'] = "";

        $this->view->assign('data', $data);
        $this->view->display('user/dashboard.tpl');

        return $response;
    }
}
