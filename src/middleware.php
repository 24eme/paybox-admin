<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

use Tuupola\Middleware\HttpBasicAuthentication;

$app->add(new HttpBasicAuthentication([
	"users" => [
		getenv("ADMIN_USER") => getenv("ADMIN_PASSWORD")
	]
]));