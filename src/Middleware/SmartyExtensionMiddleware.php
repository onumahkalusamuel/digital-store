<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\App;
use Smarty;
use Symfony\Component\HttpFoundation\Session\Session;

final class SmartyExtensionMiddleware implements MiddlewareInterface
{

    private $app;
    private $smarty;
    private $session;

    public function __construct(
        App $app,
        Smarty $smarty,
        Session $session
    ) {
        $this->smarty = $smarty;
        $this->app = $app;
        $this->session = $session;
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $this->smarty->assign('uri', $request->getUri());
        $this->smarty->assign('basePath', $this->app->getBasePath());
        $this->smarty->assign('route', $this->app->getRouteCollector()->getRouteParser());
        $this->smarty->assign('flashBag', $this->session->getFlashBag());
        $this->smarty->assign('session', $this->session);
        return $handler->handle($request);
    }
}
