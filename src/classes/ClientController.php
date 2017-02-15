<?php


class ClientController extends BaseController
{
	public function getUser($request, $response, $args)
	{
		$mapper = new ClientMapper($this->container->get('database'), $this->container->get('logger'));
		$client = $mapper->getClient($args['id']);
		$histo = $mapper->getClientHisto($args['id']);

		if ($client === false) {
			parent::throw404($request, 'No client found.');
			exit();
		}

		return $this->container->get('renderer')->render($response, 'client.phtml', ['client' => $client, 'histo' => $histo]);
	}
}