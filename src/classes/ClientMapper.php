<?php


class ClientMapper extends Mapper
{
	public function getClient($id)
	{
		$sql = "SELECT * FROM client WHERE c_pk = " . $this->db->quote($id);
		$stmt = $this->db->query($sql);

		return $stmt->fetch();
	}

	public function getClientHisto($id)
	{
		$sql = "SELECT * FROM v_paiement_effectue WHERE c_pk = " . $this->db->quote($id) . " ORDER BY y_date DESC";

		$stmt = $this->db->query($sql);
		return $this->getResults($stmt);
	}
}