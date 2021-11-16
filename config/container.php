<?php

use App\Interfaces\ResultCard\NabtebResultCardInterface;
use App\Interfaces\ResultCard\NecoResultCardInterface;
use App\Interfaces\ResultCard\WaecResultCardInterface;
use App\Interfaces\VTU\AirtelAirtimeInterface;
use App\Interfaces\VTU\AirtelDataInterface;
use App\Interfaces\VTU\AirtelSmeInterface;
use App\Interfaces\VTU\GloAirtimeInterface;
use App\Interfaces\VTU\GloDataInterface;
use App\Interfaces\VTU\GloSmeInterface;
use App\Interfaces\VTU\MtnAirtimeInterface;
use App\Interfaces\VTU\MtnDataInterface;
use App\Interfaces\VTU\MtnShareNSellInterface;
use App\Interfaces\VTU\MtnSmeInterface;
use App\Interfaces\VTU\NineMobileAirtimeInterface;
use App\Interfaces\VTU\NineMobileDataInterface;
use App\Interfaces\VTU\NineMobileSmeInterface;
use Illuminate\Container\Container as IlluminateContainer;
use Illuminate\Database\Connection;
use Illuminate\Database\Connectors\ConnectionFactory;
use Intervention\Image\ImageManager;
use Selective\BasePath\BasePathMiddleware;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

return [
    'settings' => function () {
        return require __DIR__ . '/settings.php';
    },

    'serviceProviders' => function () {
        return require __DIR__ . '/service_providers.php';
    },

    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);

        return AppFactory::create();
    },

    BasePathMiddleware::class => function (ContainerInterface $container) {
        return new BasePathMiddleware($container->get(App::class));
    },

    ErrorMiddleware::class => function (ContainerInterface $container) {
        $app = $container->get(App::class);
        $settings = $container->get('settings')['error'];

        return new ErrorMiddleware(
            $app->getCallableResolver(),
            $app->getResponseFactory(),
            (bool)$settings['display_error_details'],
            (bool)$settings['log_errors'],
            (bool)$settings['log_error_details']
        );
    },

    // Database connection
    Connection::class => function (ContainerInterface $container) {
        $factory = new ConnectionFactory(new IlluminateContainer());

        $connection = $factory->make($container->get('settings')['db']);

        // Disable the query log to prevent memory issues
        $connection->disableQueryLog();

        return $connection;
    },

    PDO::class => function (ContainerInterface $container) {
        return $container->get(Connection::class)->getPdo();
    },

    Smarty::class => function (ContainerInterface $container) {
        $smarty = new Smarty();
        $smarty->setTemplateDir($container->get('settings')['smarty']['template_dir']);
        $smarty->setCompileDir($container->get('settings')['smarty']['compile_dir']);
        $smarty->setConfigDir($container->get('settings')['smarty']['config_dir']);
        $smarty->setCacheDir($container->get('settings')['smarty']['cache_dir']);
        $smarty->debugging = $_ENV['APP_ENV'] == 'dev';
        return $smarty;
    },

    Session::class => function (ContainerInterface $container) {
        $settings = $container->get('settings')['session'];

        if (PHP_SAPI === 'cli') {
            return new Session(new MockArraySessionStorage());
        } else {
            return new Session(new NativeSessionStorage($settings));
        }
    },

    SessionInterface::class => function (ContainerInterface $container) {
        return $container->get(Session::class);
    },

    ImageManager::class => function (ContainerInterface $container) {
        return new ImageManager($container->get('settings')['image_manager']);
    },

    // Selected Interfaces
    AirtelDataInterface::class => function (ContainerInterface $container) {
        $provider = $container->get('serviceProviders')['AirtelData'];
        return new $provider;
    },
    MtnDataInterface::class => function (ContainerInterface $container) {
        $provider = $container->get('serviceProviders')['MtnData'];
        return new $provider;
    },
    GloDataInterface::class => function (ContainerInterface $container) {
        $provider = $container->get('serviceProviders')['GloData'];
        return new $provider;
    },
    NineMobileDataInterface::class => function (ContainerInterface $container) {
        $provider = $container->get('serviceProviders')['NineMobileData'];
        return new $provider;
    },
    //sme
    AirtelSmeInterface::class => function (ContainerInterface $container) {
        $provider = $container->get('serviceProviders')['AirtelSme'];
        return new $provider;
    },
    MtnSmeInterface::class => function (ContainerInterface $container) {
        $provider = $container->get('serviceProviders')['MtnSme'];
        return new $provider;
    },
    GloSmeInterface::class => function (ContainerInterface $container) {
        $provider = $container->get('serviceProviders')['GloSme'];
        return new $provider;
    },
    NineMobileSmeInterface::class => function (ContainerInterface $container) {
        $provider = $container->get('serviceProviders')['NineMobileSme'];
        return new $provider;
    },
    // airtime
    AirtelAirtimeInterface::class => function (ContainerInterface $container) {
        $provider = $container->get('serviceProviders')['AirtelAirtime'];
        return new $provider;
    },
    MtnAirtimeInterface::class => function (ContainerInterface $container) {
        $provider = $container->get('serviceProviders')['MtnAirtime'];
        return new $provider;
    },
    GloAirtimeInterface::class => function (ContainerInterface $container) {
        $provider = $container->get('serviceProviders')['GloAirtime'];
        return new $provider;
    },
    NineMobileAirtimeInterface::class => function (ContainerInterface $container) {
        $provider = $container->get('serviceProviders')['NineMobileAirtime'];
        return new $provider;
    },
    // sharensell 
    MtnShareNSellInterface::class => function (ContainerInterface $container) {
        $provider = $container->get('serviceProviders')['MtnShareNSell'];
        return new $provider;
    },
    // Result Cards
    WaecResultCardInterface::class => function (ContainerInterface $container) {
        $provider = $container->get('serviceProviders')['WaecResultCard'];
        return new $provider;
    },
    NecoResultCardInterface::class => function (ContainerInterface $container) {
        $provider = $container->get('serviceProviders')['NecoResultCard'];
        return new $provider;
    },
    NabtebResultCardInterface::class => function (ContainerInterface $container) {
        $provider = $container->get('serviceProviders')['NabtebResultCard'];
        return new $provider;
    },
];
