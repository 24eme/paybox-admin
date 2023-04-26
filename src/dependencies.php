<?php
// DIC configuration
use Psr\Container\ContainerInterface;

$container = $app->getContainer();

// load settings
$container->set('settings', function (ContainerInterface $c) {
    return require __DIR__.'/settings.php';
});

// view renderer
$container->set('renderer', function (ContainerInterface $c) {
    $settings = $c->get('settings')['renderer'];
    $renderer = new Slim\Views\PhpRenderer($settings['template_path']);
    $renderer->setLayout('base.phtml');
    return $renderer;
});

// monolog
$container->set('logger', function (ContainerInterface $c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
});

// database
$container->set('database', function (ContainerInterface $c) {
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
});

$container->set('flash', function (ContainerInterface $c) {
    return new Slim\Flash\Messages();
});

$container->set('assert', function (ContainerInterface $c) {
    return Assert\Assert::lazy();
});
