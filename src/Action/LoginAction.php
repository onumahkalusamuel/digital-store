<?php

namespace App\Action;

use App\Repositories\UsersRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;
use Symfony\Component\HttpFoundation\Session\Session;

class LoginAction
{

    protected $user;
    protected $session;

    public function __construct(Session $session, UsersRepository $user)
    {
        $this->user = $user;
        $this->session = $session;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

        $data = (array) $request->getParsedBody();

        $phone_email = trim($data['phone_email']);
        $password = $data['password'];

        // variables
        $loggedIn = false;
        $user_type = '';
        $message = '';
        $responseBody = [];

        // attempt login by phone
        $loginUser = $this->user->find(['params' => ['phone' => $phone_email]]);

        // then by email
        if (empty($loginUser->id)) {
            $loginUser = $this->user->find(['params' => ['email' => $phone_email]]);
        }

        if (empty($loginUser->id)) {
            $message = "Invalid login details";
        }

        if (empty($message)) {
            if (password_verify($password, $loginUser->password)) {
                if (empty($loginUser->status)) {
                    $message = "Sorry, it looks like your account is not active. Please chat with support for assistance.";
                }
            } else {
                $message = "Invalid login details";
            }
        }

        if (empty($message)) {
            if (!empty($loginUser->user_type)) $user_type = $loginUser->user_type;
        }

        // Get RouteParser from request to generate the urls
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        if (empty($message)) {
            $this->session->invalidate();
            $this->session->start();

            $this->session->set('id', $loginUser->id);
            $this->session->set('user_type', $loginUser->user_type);
            $this->session->set('phone', $loginUser->phone);
            $this->session->set('email', $loginUser->email);

            $responseBody['message'] = "Logged in successfully.";
            $responseBody['success'] = true;
            if ($user_type == "user") {
                $responseBody['redirect'] = $routeParser->urlFor("dashboard");
            } else {
                $responseBody['redirect'] = $routeParser->urlFor("{$user_type}-dashboard");
            }
        } else {
            $responseBody['message'] = !empty($message) ? $message : 'Invalid Login Details!';
            $responseBody['success'] = false;
        }

        $response->getBody()->write(json_encode($responseBody));

        return $response;
    }
}
