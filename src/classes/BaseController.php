<?php


use Interop\Container\ContainerInterface;
use Slim\Exception\NotFoundException;

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
        if ($request->isXhr()) {
            return $response->withJson(['message' => $message], 404);
        } else {
            $request = $request->withAttribute('404Message', $message);
            throw new NotFoundException($request, $response);
        }
    }
}
