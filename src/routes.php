<?php
// Routes

use Slim\Http\Request;

$app->get('/', function ($request, $response, $args) {
	return $this->renderer->render($response, 'index.phtml');
})->setName('index');

$app->get('/promo', function ($request, $response, $args) {
	$mapper = new PaiementMapper($this->database);
	$promos = $mapper->getPromos();

	return $this->renderer->render($response, 'promo.phtml', ['promos' => $promos]);
})->setName('liste-promo');

$app->group('/promo/{promo:[a-zA-Z0-9_]+}', function () {
	$this->get('', function ($request, $response, $args) {
		$promo_id = $args['promo'];

		$mapper = new PaiementMapper($this->database);

		if ($promo_id == 'all') {
			$paiements = $mapper->getPaiements();
		} else {
			$paiements = $mapper->getPaiementsByPromo($promo_id);
		}

		$statuses = $mapper->getStatuses();

		$routeArgsCsv = ['promo' => $promo_id, 'status' => 'EFFECTUE'];

		return $this->renderer->render($response, 'list.phtml', ['router' => $this->router, 'paiements' => $paiements,
			'statuses' => $statuses, 'args' => $args, 'routeArgsCsv' => $routeArgsCsv]);
	})->setName('promo');

	$this->get('/{status}', function (Request $request, $response, $args) {
		$promo_id = $args['promo'];
		$status = $args['status'];

		$mapper = new PaiementMapper($this->database);
		$paiements = $mapper->getPaiementsByStatus($status, $promo_id);

		$statuses = $mapper->getStatuses();

		$routeArgsCsv = ['promo' => $promo_id, 'status' => $status];

		return $this->renderer->render($response, 'list.phtml', ['router' => $this->router,
			'paiements' => $paiements, 'statuses' => $statuses, 'args' => $args, 'routeArgsCsv' => $routeArgsCsv]);
	})->setName('promo-status');

	$this->get('/{status}/csv', function ($request, $response, $args) {
		$promo_id = $args['promo'];
		$status = $args['status'];

		$mapper = new PaiementMapper($this->database);
		$paiements = $mapper->csv($status, $promo_id);

		$response = $response->withHeader('Content-Type', 'text/csv; charset=utf-8');
		$response = $response->withHeader('Content-Disposition',
			'attachment; filename=' . $promo_id . '-' . $status . '-' . date('Y-m-d') . '.csv');

		$output = fopen('php://output', 'w');
		$csvheader = ['nom', 'prenom', 'promo', 'libelleproduit', 'montant', 'status', 'date'];
		fputcsv($output, $csvheader, ';');

		foreach ($paiements as $paiement) {
			fputcsv($output, $paiement, ';');
		}
		fclose($output);

		return $response;
	})->setName('promo-status-csv');
});

$app->get('/produit', function ($request, $response, $args) {
	$mapper = new ProduitMapper($this->database);
	$produits = $mapper->getProduits();

	return $this->renderer->render($response, 'produit.phtml', ['produits' => $produits]);
})->setName('produit');

$app->get('/produit/{id:[0-9]+}', function ($request, $response, $args) {
	$mapper = new ProduitMapper($this->database);
	$produit = $mapper->getProduit($args['id']);

	return $this->renderer->render($response, 'produit.phtml', ['produit' => $produit]);
})->setName('produit-id');

$app->get('/user/{id:[0-9]+}', function ($request, $response, $args) {
	$mapper = new ClientMapper($this->database);
	$client = $mapper->getClient($args['id']);
	$histo = $mapper->getClientHisto($args['id']);

	return $this->renderer->render($response, 'client.phtml', ['client' => $client, 'histo' => $histo]);
})->setName('user-id');