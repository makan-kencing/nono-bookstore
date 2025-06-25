<?php

require '../vendor/autoload.php';
require_once '../config/routes.php';
require_once '../config/config.php';

foreach (FLIGHT_SET_VARS as $key => $value) {
    Flight::set($key, $value);
}

$twig_loader = new \Twig\Loader\FilesystemLoader(Flight::get("flight.views.path"));

Flight::register("view", "Twig\Environment", [$twig_loader, TWIG_CONFIG], function ($twig) {
    if (DEBUG) {
        $twig->addExtension(new \Twig\Extension\DebugExtension());
    }
});

Flight::map('render', function (string $template, array $data): void {
    echo Flight::view()->render($template, $data);
});

Flight::start();