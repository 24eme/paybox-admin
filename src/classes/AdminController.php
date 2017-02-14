<?php


class AdminController extends BaseController
{
	public function log($request, $response, $args)
	{
		$logfile = $this->container->get('logger')->getStream();
		if (!file_exists($logfile)) {
			touch($logfile);
		}

		$logcontent = file_get_contents($logfile);

		return $this->container->get('renderer')->render($response, 'log.phtml', ['logfile' => $logcontent]);
	}
}