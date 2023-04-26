<?php

namespace App\Mappers;

use PDOStatement;
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
     * Vérifie qu'une entrée existe
     * @param $table string Nom de la table
     * @param $where string Where clause optionnel
     *
     * @return bool
     */
    public function exist($table, $where = '')
    {
        $sql = "SELECT count(*) FROM $table $where";
        if ($stmt = $this->db->query($sql)) {
            return $stmt->fetchColumn();
        } else {
            // Erreur de requête
            self::logError('Erreur dans la requête: '
                . PHP_EOL
                . print_r($this->db->errorInfo(), true),
                'alert');
        }
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
