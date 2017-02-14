<?php


class PaiementMapper extends Mapper
{
	public function getPaiements($status = 'EFFECTUE')
	{
		$sql = "SELECT * 
			FROM v_paiement_effectue
			WHERE y_status = 'EFFECTUE'
			ORDER BY y_date DESC, c_nom";
		$stmt = $this->db->query($sql);

		return $this->getResults($stmt);
	}

	public function getPaiementsByStatus($status, $promo_id)
	{
		if (empty($status) || empty($promo_id))
			throw new Exception('Empty status');

		$sql = "SELECT *
			FROM v_paiement_effectue
			WHERE y_status = " . $this->db->quote($status);
		if ($promo_id != 'all')
			$sql .= "AND promo = " . $this->db->quote($promo_id);
		$sql .= " ORDER BY y_date DESC, c_nom";
		$stmt = $this->db->query($sql);

		return $this->getResults($stmt);
	}

	public function getPaiementsByPromo($promo, $status = 'EFFECTUE')
	{
		$sql = "SELECT * FROM v_paiement_effectue WHERE y_status = " . $this->db->quote($status) . "
			AND promo = " . $this->db->quote($promo) . " ORDER BY y_date DESC, c_nom";
		$stmt = $this->db->query($sql);

		return $this->getResults($stmt);
	}

	public function export($status, $promo_id)
	{
		if (empty($status) || empty($promo_id))
			throw new Exception('Empty status');

		$sql = "SELECT c_nom, c_prenom, promo, p_libelle, p_montant, y_status, y_date
			FROM v_paiement_effectue
			WHERE y_status = " . $this->db->quote($status);
		if ($promo_id !== 'all')
			$sql .= "AND promo = " . $this->db->quote($promo_id);
		$sql .= " ORDER BY y_date DESC, c_nom";
		$stmt = $this->db->query($sql);

		return $this->getResults($stmt);
	}

	public function getStatuses()
	{
		$sql = "SELECT DISTINCT y_status FROM v_paiement_effectue";
		$stmt = $this->db->query($sql);

		return $this->getResults($stmt);
	}

	public function getPromos()
	{
		$sql = "SELECT DISTINCT promo FROM v_paiement_effectue ORDER BY promo";
		$stmt = $this->db->query($sql);

		return $this->getResults($stmt);
	}
}