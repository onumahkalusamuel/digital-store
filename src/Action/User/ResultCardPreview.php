<?php

namespace App\Action\User;

use App\Interfaces\ResultCard\NabtebResultCardInterface;
use App\Interfaces\ResultCard\NecoResultCardInterface;
use App\Interfaces\ResultCard\WaecResultCardInterface;
use App\Repositories\TrailLogRepository;
use App\Repositories\UsersRepository;
use App\Traits\GeneralTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;
use Symfony\Component\HttpFoundation\Session\Session;
use Smarty as View;

final class ResultCardPreview
{

    use GeneralTrait;

    protected $user;
    protected $session;
    protected $trailLog;
    protected $view;
    protected $waec;
    protected $neco;
    protected $nabteb;

    public function __construct(
        UsersRepository $user,
        Session $session,
        View $view,
        TrailLogRepository $trailLog,
        WaecResultCardInterface $waec,
        NecoResultCardInterface $neco,
        NabtebResultCardInterface $nabteb
    ) {
        $this->user = $user;
        $this->session = $session;
        $this->view = $view;
        $this->trailLog = $trailLog;
        $this->waec = $waec;
        $this->neco = $neco;
        $this->nabteb = $nabteb;
    }

    public function preview(
        ServerRequestInterface $request,
        ResponseInterface $response,
        $args
    ): ResponseInterface {

        $examination = $args['examination'];
        $trans_ref = $args['trans_ref'];

        $this->view->assign('examination', $examination);

        $this->view->display('user/result-card-preview.tpl');

        return $response;
    }
}
