<?php

namespace App\Controllers;

use App\Mappers\ProduitMapper;
use App\Mappers\PaiementMapper;

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
            return parent::throw404($request, $response, 'Produit introuvable.');
        }

        $messages = $this->container->get('flash')->getMessage('Update');

        return $this->container->get('renderer')->render($response, 'produit.phtml', compact('produit', 'messages'));
    }

    public function getPaiements($request, $response, $args)
    {
        // Le produit existe ?
        $mapper = new ProduitMapper($this->container->get('database'), $this->container->get('logger'));
        $produit = $mapper->getProduit($args['id']);

        if ($produit === false) {
            return parent::throw404($request, $response, 'Produit introuvable.');
        }

        // Status par défaut
        $status = $request->getQueryParam('status', 'EFFECTUE');

        // On récupère les paiements
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
            return $this->container->get('renderer')->render($response, 'paiements.phtml', $returnedData);
        }
    }

    public function formCreateProduct($request, $response, $args)
    {
        $messages = $this->container->get('flash')->getMessage('Create');

        return $this->container->get('renderer')->render($response, 'new-produit.phtml', compact('messages'));
    }

    public function createProduct($request, $response, $args)
    {
        $mapper = new ProduitMapper($this->container->get('database'), $this->container->get('logger'));
        $form = $request->getParsedBody(); // On récupère les infos du formulaire

        $assert = $this->container->get('assert');

        $success = 'warning';
        $message = '';

        try {
            // On valide les infos du nouveau produit
            $assert
                ->that($form['libelle'], 'Libellé')->notEmpty()->string()
                ->that((int)$form['type_paiement'], 'Paiement')->integer()->between(1, 2)
                ->that($form['montant'], 'Montant')->numeric()->min(0)
                ->verifyNow();
            $form['open'] = (isset($form['open'])) ? 1 : 0;

            $new = $mapper->create($form);

            return $response->withStatus(302)->withHeader('Location', '/produit/'.$new);
        } catch (Assert\LazyAssertionException $e) {
            $message = $e->getMessage();
            $this->container->get('flash')->addMessage('Create', [$success, $message]);
        }

        return $response->withStatus(302)->withHeader('Location', '/produit/new');
    }

    public function updateProduct($request, $response, $args)
    {
        $mapper = new ProduitMapper($this->container->get('database'), $this->container->get('logger'));
        $form = $request->getParsedBody();

        $form['open'] = (isset($form['open'])) ? 1 : 0;
        $form['id'] = $args['id'];

        $success = "danger";
        $message = "";
        if ($mapper->update($form) !== false) {
            $success = "success";
            $message = "Produit mis à jour.";
        } else {
            $success = "danger";
            $message = "Une erreur s'est produite durant la mise à jour. Les logs contiennent plus d'informations.";
        }

        $this->container->get('flash')->addMessage('Update', [$success, $message]);

        return $response->withStatus(302)->withHeader('Location', '/produit/'.$args['id']);
    }

    public function export($request, $response, $args)
    {
        // Le produit existe ?
        $mapper = new ProduitMapper($this->container->get('database'), $this->container->get('logger'));
        $produit = $mapper->getProduit($args['id']);

        if ($produit === false) {
            return parent::throw404($request, $response, 'Produit introuvable.');
        }

        $format = $request->getQueryParam('format', 'csv');
        $status = $request->getQueryParam('status', 'EFFECTUE');

        //$promo = $args['promo'];

        $mapper = new PaiementMapper($this->container->get('database'), $this->container->get('logger'));
        $paiements = $mapper->export($args['id'], $status);

        $response = $response->withHeader('Content-Type', 'text/csv; charset=utf-8');
        $response = $response->withHeader('Content-Disposition',
            'attachment; filename=' . $args['id'] . '-' . $status . '-' . date('Y-m-d') . '.csv');

        $output = fopen('php://output', 'w');
        $csvheader = ['nom', 'prenom', 'email', 'libelleproduit', 'montant', 'status', 'date', 'reference'];
        fputcsv($output, $csvheader, ';');

        foreach ($paiements as $paiement) {
            fputcsv($output, $paiement, ';');
        }
        fclose($output);

        return $response;
    }
}
