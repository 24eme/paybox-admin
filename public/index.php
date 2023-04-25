<?php

use DI\Container;
use Dotenv\Dotenv;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

// Auto loader
require __DIR__ . '/../vendor/autoload.php';
/* spl_autoload_register(function ($classname) { */
/*     require("../src/classes/" . $classname . ".php"); */
/* }); */

session_start();

// Load Env variables
$dotenv = Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

// Container de dépendance (n'importe quelle implémentation PSR-11)
$container = new \DI\Container();
AppFactory::setContainer($container);

// Instantiate the app
$app = AppFactory::create();

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Register middleware
require __DIR__ . '/../src/middleware.php';

// Register routes
require __DIR__ . '/../src/routes.php';

// Run app
$app->run();
