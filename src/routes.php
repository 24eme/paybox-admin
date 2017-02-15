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

$app->get('/produit', ProductController::class . ':getAll')->setName('produit');
$app->get('/produit/', function ($request, $response, $args) {
	return $response->withStatus(302)->withHeader('Location', '/produit');
});
$app->post('/produit/new', ProductController::class . ':createProduct')->setName('create-produit');

$app->group('/produit/{id:[0-9]+}', function () {
	$this->get('', ProductController::class . ':getProduct')->setName('produit-id');
	$this->post('/edit', ProductController::class . ':updateProduct')->setName('set-produit-id');
	$this->get('/users', ProductController::class . ':getUsers')->setName('users-produit-id');
});

$app->get('/user/{id:[0-9]+}', UserController::class . ':getUser')->setName('user-id');

$app->get('/log', AdminController::class . ':log')->setName('log');