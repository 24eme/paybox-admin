<?php


class PaiementMapper extends Mapper
{
    public function getPaiements($produit = null, $status = 'EFFECTUE')
    {
        $WHERE = 'WHERE y_status = ' . $this->db->quote($status);

        if ($produit !== null) {
            $WHERE .= ' AND p_pk = ' . $this->db->quote($produit, PDO::PARAM_INT);
        }

        $sql = "SELECT * FROM v_paiement_effectue "
            . $WHERE
            . " ORDER BY y_date DESC, c_nom";
        $stmt = $this->db->query($sql);

        return $this->getResults($stmt);
    }

    public function export($produit, $status = 'EFFECTUE')
    {
        $sql = "SELECT c_nom, c_prenom, c_email, p_libelle, y_montant,
                y_status, y_date, y_reference
            FROM v_paiement_effectue
            WHERE y_status = " . $this->db->quote($status)
            . " AND p_pk = " . $this->db->quote($produit, PDO::PARAM_INT)
            . " ORDER BY y_date DESC, c_nom";
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
