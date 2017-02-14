<?php
// Routes

$app->get('/', function ($request, $response, $args) {
	return $this->renderer->render($response, 'index.phtml');
})->setName('index');

/* Plus utilisé
$app->group('/promo', function() {
	$this->get('/', PromoController::class.'getAll')->setName('liste-promo');
	$this->get('/{promo:[a-zA-Z0-9_]+}[/{status}]', PromoController::class.'getPromo')->setName('promo');
	$this->get('/{promo:[a-zA-Z0-9_]+}/export', PromoController::class.'export')->setName('export-promo');
});
*/

$app->group('/produit', function () {
	$this->get('/', ProductController::class . ':getAll')->setName('produit');
	$this->post('/new', ProductController::class . ':createProduct')->setName('create-produit');
	$this->get('/{id:[0-9]+}', ProductController::class . ':getProduct')->setName('produit-id');
	$this->post('/{id:[0-9]+}/edit', ProductController::class . ':updateProduct')->setName('set-produit-id');
	$this->get('/{id:[0-9]+}/users', ProductController::class . ':getUsers')->setName('users-produit-id');
});

$app->get('/user/{id:[0-9]+}', UserController::class . ':getUser')->setName('user-id');

$app->get('/log', AdminController::class . ':log')->setName('log');