<?php

namespace App\Controllers;

use App\Mappers\ClientMapper;

class ClientController extends BaseController
{
    public function getUser($request, $response, $args)
    {
        $mapper = new ClientMapper($this->container->get('database'), $this->container->get('logger'));
        $client = $mapper->getClient($args['id']);
        $histo = $mapper->getClientHisto($args['id']);

        if ($client === false) {
            return parent::throw404($request, $response, 'Client introuvable.');
        }

        return $this->container->get('renderer')->render($response, 'client.phtml', compact('client', 'histo'));
    }
}
