<?php

class ReferenceController extends BaseController
{
    public function getReference($request, $response, $args)
    {
        $reference = $args['reference'];

        $mapper = new ReferenceMapper($this->container->get('database'), $this->container->get('logger'));
        $mapper->setReference($reference);

        if (! $mapper->exist('v_paiement_effectue', 'WHERE y_reference = ' . $mapper->getReference())) {
            return parent::throw404($request, $response, 'Référence introuvable.');
        }

        $referenceInfo['user']    = $mapper->getUserInfo();
        $referenceInfo['produit'] = $mapper->getProduitInfo();
        $referenceInfo['paiements']   = $mapper->getPaiements();

        if ($referenceInfo === false) {
            return parent::throw404($request, $response, 'Référence introuvable.');
        }

        return $this->container->get('renderer')->render($response, 'reference.phtml', compact('reference', 'referenceInfo'));
    }
}
