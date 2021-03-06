<?php

namespace App\Action\User;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Smarty as View;

final class Menu
{
    protected $view;

    public function __construct(
        View $view
    ) {
        $this->view = $view;
    }

    public function init(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $this->view->display('user/menu.tpl');
        return $response;
    }
}
