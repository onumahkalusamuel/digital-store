<?php 
$app = include_once __DIR__ . '/config/bootstrap.php';
return $app->getContainer()->get('settings')['phoenix'];