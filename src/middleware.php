<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Middleware\ContentLengthMiddleware;
use Slim\Psr7\Response;
use Tuupola\Middleware\HttpBasicAuthentication;

$app->addRoutingMiddleware();

$app->add(new HttpBasicAuthentication([
    "users" => [
        $_ENV["ADMIN_USER"] => $_ENV["ADMIN_PASSWORD"]
    ]
]));

$errorMiddleware = $app->addErrorMiddleware((bool) $_ENV['DISPLAY_ERRORS'], true, true);

// Set the Not Found Handler
$errorMiddleware->setErrorHandler(
    HttpNotFoundException::class,
    function (ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails) {
        $response = new Response();
        $file = $c->get('settings')['renderer']['template_path'].'404.phtml';
        $body = str_replace('%%MESSAGE%%', $request->getAttribute('404Message'), file_get_contents($file));

        $response->getBody()->write($body);

        return $response->withStatus(404);
    }
);

// en dernier
$contentLengthMiddleware = new ContentLengthMiddleware();
$app->add($contentLengthMiddleware);
