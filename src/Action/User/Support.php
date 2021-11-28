<?php

namespace App\Action\User;

use App\Repositories\SettingsRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Smarty as View;

final class Support
{
    protected $view;
    protected $settings;

    public function __construct(
        View $view,
        SettingsRepository $settings
    ) {
        $this->view = $view;
        $this->settings = $settings;
    }

    public function init(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $this->view->assign('whatsapp', $this->settings->contact__whatsapp);
        $this->view->assign('facebook', $this->settings->contact__facebook);
        $this->view->assign('instagram', $this->settings->contact__instagram);
        $this->view->assign('phone', $this->settings->contact__phone);
        $this->view->assign('email', $this->settings->contact__email);
        $this->view->display('user/support.tpl');
        return $response;
    }
}
