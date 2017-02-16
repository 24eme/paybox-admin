<?php


class ProduitController extends BaseController
{
	public function getAll($request, $response, $args)
	{
		$mapper = new ProduitMapper($this->container->get('database'), $this->container->get('logger'));
		$produits = $mapper->getProduits();

		return $this->container->get('renderer')->render($response, 'produit.phtml', ['produits' => $produits]);
	}

	public function getProduct($request, $response, $args)
	{
		$mapper = new ProduitMapper($this->container->get('database'), $this->container->get('logger'));
		$produit = $mapper->getProduit($args['id']);

		if ($produit === false) {
			return parent::throw404($request, $response, 'No product found.');
		}

		return $this->container->get('renderer')->render($response, 'produit.phtml', ['produit' => $produit]);
	}

	public function getPaiements($request, $response, $args)
	{
		$status = $request->getQueryParam('status', 'EFFECTUE');

		$mapper = new PaiementMapper($this->container->get('database'), $this->container->get('logger'));
		$users = $mapper->getPaiements($args['id'], $status);
		$statuses = $mapper->getStatuses();

		if ($request->isXhr()) {
			$data = [
				'produit' => $args['id'],
				'status' => $status,
				'count' => count($users),
				'users' => $users
			];
			return $response->withJson($data);
		} else {
			$returnedData = [
				'router' => $this->container->get('router'),
				'produit' => $args['id'],
				'status' => $status,
				'statuses' => $statuses,
				'users' => $users
			];
			return $this->container->get('renderer')->render($response, 'produit-user.phtml', $returnedData);
		}
	}

	public function createProduct($request, $response, $args)
	{

	}

	public function updateProduct($request, $response, $args)
	{

	}

	public function export($request, $response, $args)
	{
		$format = $request->getQueryParam('format', 'csv');
		$status = $request->getQueryParam('status', 'EFFECTUE');

		//$promo = $args['promo'];

		$mapper = new PaiementMapper($this->container->get('database'), $this->container->get('logger'));
		$paiements = $mapper->export($args['id'], $status);

		$response = $response->withHeader('Content-Type', 'text/csv; charset=utf-8');
		$response = $response->withHeader('Content-Disposition',
			'attachment; filename=' . $args['id'] . '-' . $status . '-' . date('Y-m-d') . '.csv');

		$output = fopen('php://output', 'w');
		$csvheader = ['nom', 'prenom', 'libelleproduit', 'montant', 'status', 'date'];
		fputcsv($output, $csvheader, ';');

		foreach ($paiements as $paiement) {
			fputcsv($output, $paiement, ';');
		}
		fclose($output);

		return $response;
	}
}