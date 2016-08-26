<?php


abstract class Mapper
{
	/** @var $db PDO */
	protected $db;

	/**
	 * Mapper constructor.
	 * @param $db
	 */
	public function __construct($db)
	{
		$this->db = $db;
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
}