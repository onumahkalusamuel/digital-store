<?php

use App\Middleware\JsonResponseMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {

    // api routes
    $app->group('/api', function (RouteCollectorProxy $group) {

        // cors/preflight
        $group->options('{routes:.+}', function ($req, $res, $args) {
            return $res;
        });

        $group->get('[/]', function ($request, $response) {
            $response->getBody()->write(json_encode([
                'message' => 'Welcome to the API home. Please check documentation.'
            ]));
            return $response->withStatus(404);
        });

        $group->get(
            '/price-list/sme-data/{network}[/]',
            [\App\Action\User\SMEData::class, 'priceList']
        )->setName('price-list-sme-data');

        $group->get(
            '/price-list/vtu-data/{network}[/]',
            [\App\Action\User\VTUData::class, 'priceList']
        )->setName('price-list-vtu-data');

        $group->post(
            '/quick-buy[/]',
            [\App\Action\Api\QuickBuy::class, 'init']
        )->setName('api-quick-buy');

        // payment callback
        $group->get('/payment-callback[/]', \App\Action\Api\PaymentCallback::class)->setName('payment-callback');

        // catch-all
        $group->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '{routes:.+}', function ($request, $response) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'The requested resource was not found.'
            ]));
            return $response->withStatus(404);
        });
    })->addMiddleware(new JsonResponseMiddleware);
};
