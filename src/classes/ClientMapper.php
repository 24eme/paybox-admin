<?php


class ClientMapper extends Mapper
{
	public function getClient($id)
	{
		$sql = "SELECT count(*) FROM client WHERE c_pk = " . $this->db->quote($id);
		if ($stmt = $this->db->query($sql)) {
			if ($stmt->fetchColumn() > 0) {
				$sql = "SELECT * FROM client WHERE c_pk = " . $this->db->quote($id);
				$stmt = $this->db->query($sql);

				return $stmt->fetch();
			} else return false; // pas de résultat
		} else {
			// Erreur de requête
			parent::logError('Erreur dans la requête: '
				. PHP_EOL
				. print_r($this->db->errorInfo(), true),
				'alert');
		}
	}

	public function getClientHisto($id)
	{
		$sql = "SELECT * FROM v_paiement_effectue WHERE c_pk = " . $this->db->quote($id) . " ORDER BY y_date DESC";

		$stmt = $this->db->query($sql);
		return $this->getResults($stmt);
	}
}