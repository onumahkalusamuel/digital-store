<?php

namespace App\Action\User;

use App\Repositories\TrailLogRepository;
use App\Traits\GeneralTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Smarty as View;

final class History
{
    use GeneralTrait;

    protected $view;
    protected $trailLog;

    public function __construct(
        View $view,
        TrailLogRepository $trailLog
    ) {
        $this->view = $view;
        $this->trailLog = $trailLog;
    }

    public function init(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

        $history = $this->trailLog->readPaging([]);

        $history['data'] = $this->toArray($history['data']);

        foreach ($history['data'] as $key => $h) {
            $date = strtotime($history['data'][$key]['created_at']);
            $history['data'][$key]['created_at'] = date("d-M-Y", $date);
        }

        $this->view->assign('history', $history);

        $this->view->display('user/history.tpl');

        return $response;
    }
}
