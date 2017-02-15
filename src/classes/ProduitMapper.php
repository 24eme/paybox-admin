<?php


class ProduitMapper extends Mapper
{
	public function getProduit($id)
	{
		$sql = "SELECT count(*) FROM produits WHERE p_pk = " . $this->db->quote($id, PDO::PARAM_INT);
		if ($stmt = $this->db->query($sql)) {
			if ($stmt->fetchColumn() > 0) {
				$sql = "SELECT * FROM produits WHERE p_pk = " . $this->db->quote($id, PDO::PARAM_INT);
				$stmt = $this->db->query($sql);

				return $stmt->fetch();
			} else return false; // pas de résultat
		} else {
			// Erreur de requête
			parent::logError('Erreur dans la requête: '
				. PHP_EOL
				. print_r($this->db->errorInfo(), true),
				'alert');
			return false;
		}
	}

	public function getProduits()
	{
		$sql = "SELECT * FROM produits";
		$stmt = $this->db->query($sql);

		return $this->getResults($stmt);
	}
}