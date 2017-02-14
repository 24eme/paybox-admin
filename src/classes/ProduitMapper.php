<?php


class ProduitMapper extends Mapper
{
	public function getProduit($id)
	{
		$sql = "SELECT * FROM produits WHERE p_pk = " . $this->db->quote($id, PDO::PARAM_INT);
		$stmt = $this->db->query($sql);

		return $stmt->fetch();
	}

	public function getProduits()
	{
		$sql = "SELECT * FROM produits";
		$stmt = $this->db->query($sql);

		return $this->getResults($stmt);
	}

	public function getUsers($pid)
	{
		$sql = "SELECT * FROM v_paiement_effectue WHERE p_pk = "
			. $this->db->quote($pid, PDO::PARAM_INT);

		$stmt = $this->db->query($sql);

		return $this->getResults($stmt);
	}
}