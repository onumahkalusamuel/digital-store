<?php

use App\Middleware\UserAuthMiddleware;
use App\Middleware\JsonResponseMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {

    $app->group('/user/', function (RouteCollectorProxy $group) {

        //account logs= == trx
        $group->get('account-logs[/]', \App\Action\User\AccountLogsView::class)->setName('user-account-logs');

        //dashboard
        $group->get('dashboard[/]', \App\Action\User\UserDashboardAction::class)->setName('user-dashboard');

        // user profile
        $group->get('profile[/]', \App\Action\User\ProfileView::class)->setName('user-profile');
        $group->post('profile[/]', \App\Action\User\ProfileAction::class);

        $group->get('vtu-data/{network}[/]', [\App\Action\User\VTUData::class, 'init'])->setName('vtu-data');
        $group->post('vtu-data/{network}[/]', [\App\Action\User\VTUData::class, 'confirm']);

        $group->get('sme-data/{network}[/]', [\App\Action\User\SMEData::class, 'init'])->setName('sme-data');
        $group->post('sme-data/{network}[/]', [\App\Action\User\SMEData::class, 'confirm']);

        $group->get('vtu-airtime/{network}[/]', [\App\Action\User\VTUAirtime::class, 'init'])->setName('vtu-airtime');
        $group->post('vtu-airtime/{network}[/]', [\App\Action\User\VTUAirtime::class, 'confirm']);

        $group->get('sns-airtime/{network}[/]', [\App\Action\User\SNSAirtime::class, 'init'])->setName('sns-airtime');
        $group->post('sns-airtime/{network}[/]', [\App\Action\User\SNSAirtime::class, 'confirm']);

        $group->get('result-card/{examination}[/]', [\App\Action\User\ResultCard::class, 'init'])->setName('result-card');
        $group->post('result-card/{examination}[/]', [\App\Action\User\ResultCard::class, 'confirm']);
        $group->get(
            'result-card/{examination}/{trans_ref}[/]',
            [\App\Action\User\ResultCardPreview::class, 'preview']
        )->setName('result-card-preview');

        $group->get('payments[/]', [\App\Action\User\Payments::class, 'init'])->setName('payments');
        $group->post('payments[/]', [\App\Action\User\Payments::class, 'initiatePayment']);
    })
        ->add(UserAuthMiddleware::class);
};
