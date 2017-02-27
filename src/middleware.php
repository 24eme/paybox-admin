<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

use Tuupola\Middleware\HttpBasicAuthentication;

$app->add(new HttpBasicAuthentication([
	"users" => [
		"administrator" => getenv("ADMIN_PASSWORD")
	]
]));