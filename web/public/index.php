<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/routes.php';
require_once __DIR__ . '/../config/config.php';


$whoops = new Whoops\Run;
if (DEBUG)
    $whoops->pushHandler(new Whoops\Handler\PrettyPageHandler);
else
    $whoops->pushHandler(function ($e) {
        echo 'Todo: Friendly error page and send an email to the developer';
    });
$whoops->register();


