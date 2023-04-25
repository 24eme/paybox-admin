<?php
// Routes

$app->get('/', function ($request, $response, $args) {
    return $this->get('renderer')->render($response, 'index.phtml');
})->setName('index');

/* Plus utilisé
$app->group('/promo', function() {
    $this->get('/', PromoController::class.'getAll')->setName('liste-promo');
    $this->get('/{promo:[a-zA-Z0-9_]+}[/{status}]', PromoController::class.'getPromo')->setName('promo');
    $this->get('/{promo:[a-zA-Z0-9_]+}/export', PromoController::class.'export')->setName('export-promo');
});
*/

$app->get('/produit', ProduitController::class . ':getAll')->setName('produit');
$app->get('/produit/', function ($request, $response, $args) {
    return $response->withStatus(302)->withHeader('Location', '/produit');
});

$app->get('/produit/new', ProduitController::class . ':formCreateProduct')->setName('form-create-produit');
$app->post('/produit/new', ProduitController::class . ':createProduct')->setName('create-produit');

$app->group('/produit/{id:[0-9]+}', function (\Slim\Routing\RouteCollectorProxy $app) {
    $app->get('', ProduitController::class . ':getProduct')->setName('produit-id');
    $app->post('/edit', ProduitController::class . ':updateProduct')->setName('set-produit-id');
    $app->get('/paiements', ProduitController::class . ':getPaiements')->setName('paiements');
    $app->get('/paiements/export', ProduitController::class . ':export')->setName('export');
});

$app->get('/user/{id:[0-9]+}', ClientController::class . ':getUser')->setName('user-id');

$app->get('/ref/{reference}', ReferenceController::class . ':getReference')->setName('reference');
