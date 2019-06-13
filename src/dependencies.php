<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    $renderer = new Slim\Views\PhpRenderer($settings['template_path']);
    $renderer->setLayout('base.phtml');
    return $renderer;
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};

// database
$container['database'] = function ($c) {
    $settings = $c->get('settings')['database'];
    if ($settings['driver'] === 'sqlite') {
        $db = new PDO($settings['driver'] . ':' . $settings['base']);
    } else {
        $db = new PDO($settings['driver']
            . ':host=' . $settings['host']
            . ';dbname=' . $settings['base']
            . ';charset=UTF8',
            $settings['user'],
            $settings['pass']
        );
    }
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $db;
};

$container['flash'] = function ($c) {
    return new Slim\Flash\Messages();
};

$container['assert'] = function ($c) {
    return Assert\Assert::lazy();
};

// 404
unset($container['notFoundHandler']);
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        $response = new \Slim\Http\Response(404);
        $file = $c->get('settings')['renderer']['template_path'].'404.phtml';

        $body = str_replace('%%MESSAGE%%', $request->getAttribute('404Message'), file_get_contents($file));

        return $response->write($body);
    };
};
