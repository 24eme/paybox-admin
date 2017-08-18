<?php


class ClientController extends BaseController
{
    public function getUser($request, $response, $args)
    {
        $mapper = new ClientMapper($this->container->get('database'), $this->container->get('logger'));
        $client = $mapper->getClient($args['id']);
        $histo = $mapper->getClientHisto($args['id']);

        if ($client === false) {
            return parent::throw404($request, $response, 'No client found.');
        }

        return $this->container->get('renderer')->render($response, 'client.phtml', compact('client', 'histo'));
    }
}
