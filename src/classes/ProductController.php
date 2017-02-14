<?php


class ProductController extends BaseController
{
	public function getAll($request, $response, $args)
	{
		$mapper = new ProduitMapper($this->container->get('database'));
		$produits = $mapper->getProduits();

		return $this->container->get('renderer')->render($response, 'produit.phtml', ['produits' => $produits]);
	}

	public function getProduct($request, $response, $args)
	{
		$mapper = new ProduitMapper($this->container->get('database'));
		$produit = $mapper->getProduit($args['id']);

		if ($this->getNumRows($produit) === 0) {
			self::throw404($request, $response, 'No product found.');
		}

		return $this->container->get('renderer')->render($response, 'produit.phtml', ['produit' => $produit]);
	}

	public function getUsers($request, $response, $args)
	{
		$mapper = new ProduitMapper($this->container->get('database'));
		$users = $mapper->getUsers($args['id']);

		if ($request->isXhr()) {
			$data = [
				'produit' => $args['id'],
				'count' => count($users),
				'users' => $users
			];
			return $response->withJson($data);
		} else {
			$returnedData = [
				'produit' => $args['id'],
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
}