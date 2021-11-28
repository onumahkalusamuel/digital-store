<?php

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Middleware\JsonResponseMiddleware;

return function (App $app) {

    $app->group('/', function (RouteCollectorProxy $group) {

        // page views
        $group->get('', \App\Action\PageView::class)->setName('home');

        // ajax calls
        $group->group('', function (RouteCollectorProxy $group) {
            $group->post('register[/]', \App\Action\RegisterAction::class);
            $group->post('login[/]', \App\Action\LoginAction::class);
            $group->post('reset-password[/]', \App\Action\ResetPasswordAction::class);
            $group->post('reset[/]', \App\Action\ResetUpdateAction::class);
            $group->post('contact-us[/]', \App\Action\ContactUsAction::class)->setName('contact-us-form');
        })->addMiddleware(new JsonResponseMiddleware);

        // Authentication pages
        $group->get('login[/]', function ($request, $response) {
            $this->get(Smarty::class)->display('auth/login.tpl');
            return $response;
        })->setName('login');

        $group->get('register[/]', function ($request, $response) {
            $this->get(Smarty::class)->display('auth/register.tpl');
            return $response;
        })->setName('register');

        $group->get('reset-password[/]', function ($request, $response) {
            $this->get(Smarty::class)->display('auth/reset-password.tpl');
            return $response;
        })->setName('reset-password');

        $group->get('help[/]', [\App\Action\Help::class, 'init'])->setName('help');

        $group->get('reset[/]', \App\Action\ResetUpdateView::class)->setName('password-reset-link');

        $group->get('logout[/]', \App\Action\LogoutAction::class)->setName('logout');

        $group->get('ref/{referral_code}[/]', \App\Action\AffiliatesAction::class)->setName('ref');

        $group->get(
            'payment-redirect/{transaction_id}[/]',
            \App\Action\PaymentRedirect::class
        )->setName('payment-redirect');

        $group->get('prices[/]', \App\Action\Prices::class)->setName('prices');

        //catch-all page
        $group->get('page/{page}', \App\Action\PageView::class)->setName('page');
    });
};
