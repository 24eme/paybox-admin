<?php


class PromoController extends BaseController
{
	public function getAll($request, $response, $args)
	{
		$mapper = new PaiementMapper($this->container->get('database'), $this->container->get('logger'));
		$promos = $mapper->getPromos();

		return $this->container->get('renderer')->render($response, 'promo.phtml', ['promos' => $promos]);
	}

	public function getPromo($request, $response, $args)
	{
		$status = (!empty($args['status'])) ? $args['status'] : 'EFFECTUE';
		$promo_id = $args['promo'];

		$mapper = new PaiementMapper($this->container->get('database'), $this->container->get('logger'));

		if ($promo_id == 'all') {
			$paiements = $mapper->getPaiements($status);
		} else {
			$paiements = $mapper->getPaiementsByPromo($promo_id, $status);
		}

		if ($request->isXhr()) {
			$data = [
				'promo' => $promo_id,
				'count' => count($paiements),
				'status' => $status,
				'paiements' => $paiements
			];
			return $response = $response->withJson($data);
		} else {
			$statuses = $mapper->getStatuses();
			$returnedData = [
				'router' => $this->container->get('router'),
				'paiements' => $paiements,
				'statuses' => $statuses,
				'args' => $args
			];
			return $this->container->get('renderer')->render($response, 'list.phtml', $returnedData);
		}
	}

	public function export($request, $response, $args)
	{
		$format = $request->getQueryParam('format', 'csv');
		$status = $request->getQueryParam('status', 'EFFECTUE');

		$promo = $args['promo'];

		$mapper = new PaiementMapper($this->container->get('database'), $this->container->get('logger'));
		$paiements = $mapper->export($status, $promo);

		$response = $response->withHeader('Content-Type', 'text/csv; charset=utf-8');
		$response = $response->withHeader('Content-Disposition',
			'attachment; filename=' . $promo . '-' . $status . '-' . date('Y-m-d') . '.csv');

		$output = fopen('php://output', 'w');
		$csvheader = ['nom', 'prenom', 'promo', 'libelleproduit', 'montant', 'status', 'date'];
		fputcsv($output, $csvheader, ';');

		foreach ($paiements as $paiement) {
			fputcsv($output, $paiement, ';');
		}
		fclose($output);

		return $response;
	}
}