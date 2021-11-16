<?php

// Error reporting for production
error_reporting(0);
ini_set('display_errors', '0');

// load env variables
$envPath = dirname(__DIR__) . '/.env';
(new App\Helpers\DotEnvLoader($envPath))->load();

// current env
$env = $_ENV["APP_ENV"];

// Timezone
date_default_timezone_set($_ENV['TIMEZONE']);

// Settings
$settings = [];

// Path settings
$settings['root'] = dirname(__DIR__);
$settings['temp'] = $settings['root'] . '/tmp';
$settings['public'] = $settings['root'] . '/public';
$settings['upload_dir'] = $settings['public'] . '/uploads';
$settings['assets_dir'] = $settings['public'] . '/assets';

// for smarty
$settings['smarty'] = [
    'template_dir' => $settings['root'] . '/resources/views/',
    'compile_dir' => $settings['root'] . '/smarty/tmpl_c/',
    'config_dir' => $settings['root'] . '/smarty/config/',
    'cache_dir' => $settings['root'] . '/smarty/cache/'
];

// Error Handling Middleware settings
$settings['error'] = [
    // Should be set to false in production
    'display_error_details' => (bool) $env == "dev",
    'log_errors' => true,
    'log_error_details' => true,
];

// Database settings
$settings['db'] = [
    'driver' => 'mysql',
    'host' => $_ENV['DBHOST'],
    'username' => $_ENV['DBUSER'],
    'database' => $_ENV['DBNAME'],
    'password' => $_ENV['DBPASS'],
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'options' => [
        // Turn off persistent connections
        PDO::ATTR_PERSISTENT => false,
        // Enable exceptions
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // Emulate prepared statements
        PDO::ATTR_EMULATE_PREPARES => true,
        // Set default fetch mode to array
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // Set character set
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci'
    ],
];

// phoenix
$settings['phoenix'] = [
    'migration_dirs' => [
        'first' => __DIR__ . '/../resources/migrations',
    ],
    'environments' => [
        'local' => [
            'adapter' => 'mysql',
            'host' => $_ENV['DBHOST'],
            'username' => $_ENV['DBUSER'],
            'password' => $_ENV['DBPASS'],
            'db_name' => $_ENV['DBNAME'],
            'charset' => 'utf8',
        ],
        'production' => [
            'adapter' => 'mysql',
            'host' => $_ENV['DBHOST'],
            'username' => $_ENV['DBUSER'],
            'password' => $_ENV['DBPASS'],
            'db_name' => $_ENV['DBNAME'],
            'charset' => 'utf8',
        ],
    ],
    'default_environment' => 'local',
    'log_table_name' => 'phoenix_log',
];

// email settings
$settings['smtp'] = [
    'email' => $_ENV['SMTP_EMAIL'],
    'password' => $_ENV['SMTP_PASSWORD'],
    'name' => $_ENV['SMTP_SENDER_NAME'],
    'host' => $_ENV['SMTP_HOST']
];

// Session
$settings['session'] = [
    'name' => 'webapp',
    'cache_expire' => 0,
];

$settings['image_manager'] = [
    'driver' => 'gd',
];

return $settings;
