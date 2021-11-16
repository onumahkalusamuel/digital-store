<?php

namespace App\Middleware;

use App\Repositories\UsersRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response as Psr7Response;
use Slim\Routing\RouteContext;
use Smarty;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Middleware for checking if current user is an admin or not
 */
final class UserAuthMiddleware implements MiddlewareInterface
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var Smarty
     */
    private $smarty;

    /**
     * @var UsersRepository
     */
    private $user;

    public function __construct(Session $session, Smarty $smarty, UsersRepository $user)
    {
        $this->session = $session;
        $this->smarty = $smarty;
        $this->user = $user;
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        if ($this->session->get('user_type') === "user") {
            // User is logged in
            // fetch balances and attach to smarty
            $balances = (array) $this->user->readSingle([
                'id' => $this->session->get('id'),
                'select' => ['balance', 'loyalty_points']
            ]);
            $this->smarty->assign('balances', $balances);
            return $handler->handle($request);
        }

        // User is not logged in. Redirect to login page.
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        $url = $routeParser->urlFor('login');

        $response = new Psr7Response();

        return $response->withStatus(302)->withHeader('Location', $url);
    }
}
