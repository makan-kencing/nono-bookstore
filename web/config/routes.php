<?php declare(strict_types=1);

define("ROUTES", [
    ['GET', '/', function () {
        echo 'Index';
    }],
    ['GET', '/hello-world', function () {
        echo 'Hello World';
    }],
    ['GET', '/another-route', function () {
        echo 'This works too';
    }],
]);