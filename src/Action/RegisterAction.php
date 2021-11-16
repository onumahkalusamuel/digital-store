<?php

namespace App\Action;

use App\Helpers\SendMail;
use App\Repositories\UsersRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;
use Symfony\Component\HttpFoundation\Session\Session;

final class RegisterAction
{
    private $user;
    private $session;
    private $mail;

    public function __construct(
        UsersRepository $user,
        Session $session,
        SendMail $sendMail
    ) {
        $this->user = $user;
        $this->session = $session;
        $this->mail = $sendMail;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

        // used to track progress
        $message = false;

        // Collect input from the HTTP request
        $data = (array) $request->getParsedBody();

        $referral_code = $this->session->get('referral_code') ?? '';
        $fullname = trim($data['fullname']);
        $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        $password = substr(md5($email), 3, 4) . rand(111, 999);
        $phone = trim($data['phone']);

        if (empty($message) && empty($email)) {
            $message = "Please enter a valid email.";
        }

        if (empty($message) && $this->user->emailInUse($email)) {
            $message = "Email address already in use";
        }

        if (empty($message) && empty($phone)) {
            $message = "Please enter a valid phone number";
        }

        if (empty($message) && $this->user->phoneInUse($phone)) {
            $message = "Phone number already in use";
        }

        if (empty($message) && (empty($fullname) || strlen($fullname) < 5)) {
            $message = "A valid name is required";
        }

        if (empty($message)) {
            // Invoke the Domain with inputs and retain the result
            $userId = $this->user->create(['data' => [
                'fullname' => $fullname,
                'email' => $email,
                'phone' => $phone,
                'user_type' => 'user',
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'status' => 1
            ]]);
        }

        // responses
        if (empty($message) && !empty($userId)) {

            // send mail
            $this->mail->sendRegistrationEmail($email, $fullname, $phone, $password);

            // add referral code 
            $this->user->update([
                'id' => $userId,
                'data' => [
                    'referral_code' => strtoupper(substr(uniqid(), 9)) . $userId
                ]
            ]);

            if ($userId == 1) {
                // admin detected
                $this->user->update(['id' => $userId, 'data' => ['user_type' => 'admin']]);
            } elseif (!empty($referral_code)) {
                $ref = $this->user->find(['params' => [
                    'referral_code' => $referral_code
                ]]);

                if (!empty($ref->ID)) {
                    try {

                        $this->user->update([
                            'id' => $userId,
                            'data' => ['upline' => $referral_code]
                        ]);
                        // inform user
                        $this->mail->sendDirectReferralSignupEmail(
                            $ref->email,
                            $ref->fullname,
                            $fullname,
                            $phone,
                            $email
                        );
                    } catch (\Exception $e) {
                    }
                }
            }

            // Get RouteParser from request to generate the urls
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();

            $url = $routeParser->urlFor("login");

            $response->getBody()->write(json_encode([
                'success' => true,
                'message' => "Account registered successfully. Check email for login details.",
                'redirect' => $url
            ]));

            // Redirect to protected page
            return $response;
        }

        $message = $message ?? 'Unable to process request at the moment. Please try again later.';

        $response->getBody()->write(json_encode([
            'success' => false,
            'message' => $message
        ]));

        return $response;
    }
}
