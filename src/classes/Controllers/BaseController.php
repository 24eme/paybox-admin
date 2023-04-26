<?php

namespace App\Controllers;

use Psr\Container\ContainerInterface;
use Slim\Exception\HttpNotFoundException;

abstract class BaseController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getNumRows(array $result)
    {
        return count($result);
    }

    public function throw404($request, $response, $message = '')
    {
        if ($request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest') {
            return $response->withJson(['message' => $message], 404);
        } else {
            $request = $request->withAttribute('404Message', $message);
            throw new HttpNotFoundException($request);
        }
    }
}
