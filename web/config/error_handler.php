<?php

declare(strict_types=1);

use App\Core\View;
use App\Exception\ForbiddenException;
use App\Exception\MethodNotAllowedException;
use App\Exception\NotFoundException;
use App\Exception\Wrapper\ApiExceptionWrapper;
use App\Exception\Wrapper\WebExceptionWrapper;
use Whoops\Handler\Handler;
use Whoops\Handler\PrettyPageHandler;

$whoops = new Whoops\Run();
if ($_ENV['APP_ENV'] == 'dev') {
    $whoops->pushHandler(new PrettyPageHandler());
} else {
    $whoops->pushHandler(function (Throwable $_) {
        http_response_code(500);
        echo View::render('webstore/error/500.php');

        return Handler::QUIT;
    });
}
$whoops->pushHandler(function (Throwable $e) {
    if (!$e instanceof WebExceptionWrapper) {
        return HANDLER::DONE;
    }

    global $whoops;
    $e = $e->original;
    $whoops->sendHttpCode($e->getCode());

    if ($e instanceof ForbiddenException) {
        echo View::render('webstore/errors/404.php');
    } elseif ($e instanceof NotFoundException) {
        echo View::render('webstore/errors/404.php');
    } elseif ($e instanceof MethodNotAllowedException) {
        echo View::render('webstore/errors/404.php');
    } else {
        echo View::render('webstore/errors/500.php');
    }

    return Handler::QUIT;
});
$whoops->pushHandler(function (Throwable $e) {
    if (!$e instanceof ApiExceptionWrapper) {
        return HANDLER::DONE;
    }

    global $whoops;
    $e = $e->original;
    $whoops->sendHttpCode($e->getCode());
    header('Content-Type: application/json');

    echo json_encode(['hi' => 'world']);

    return HANDLER::QUIT;
});
$whoops->register();
