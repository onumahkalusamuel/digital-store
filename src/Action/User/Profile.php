<?php

namespace App\Action\User;

use App\Repositories\UsersRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Smarty as View;

final class Profile
{
    protected $view;
    protected $user;

    public function __construct(
        View $view,
        UsersRepository $user
    ) {
        $this->view = $view;
        $this->user = $user;
    }

    public function init(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $this->view->display('user/profile.tpl');
        return $response;
    }

    public function updateProfile(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $data = (array) $request->getParsedBody();
        $this->view->display('user/profile.tpl');
        return $response;
    }
}
