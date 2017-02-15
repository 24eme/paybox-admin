<?php


use Interop\Container\ContainerInterface;

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
			$response = $response->withStatus(404);
			return $this->container->get('renderer')->render($response, '404.phtml', ['message' => $message]);
		}
	}
}