<?php


use Monolog\Logger;

abstract class Mapper
{
	/** @var $db PDO */
	protected $db;

	/** @var  $logger */
	protected $logger;

	/**
	 * Mapper constructor.
	 * @param $db
	 * @param $logger
	 */
	public function __construct($db, Logger $logger)
	{
		$this->db = $db;
		$this->logger = $logger;
	}

	/**
	 * @param PDOStatement $stmt
	 * @return array
	 */
	public function getResults(PDOStatement $stmt)
	{
		$results = [];
		while ($row = $stmt->fetch()) {
			$results[] = $row;
		}
		return $results;
	}

	public function logError($message, $status = 'info')
	{
		$this->logger->$status($message);
	}
}