<?php


class UserController extends BaseController
{
	public function getUser($request, $response, $args)
	{
		$mapper = new ClientMapper($this->container->get('database'));
		$client = $mapper->getClient($args['id']);
		$histo = $mapper->getClientHisto($args['id']);

		return $this->container->get('renderer')->render($response, 'client.phtml', ['client' => $client, 'histo' => $histo]);
	}
}