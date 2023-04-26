<?php

namespace App\Mappers;

class ReferenceMapper extends Mapper
{
    protected $reference;

    public function setReference($reference)
    {
        $this->reference = $this->db->quote($reference);
    }

    public function getReference()
    {
        return $this->reference;
    }

    public function getUserInfo()
    {
        $sql = "SELECT c_pk, c_nom, c_prenom, c_email
                FROM v_paiement_effectue
                WHERE y_reference = " . $this->reference
                ;
        $stmt = $this->db->query($sql);

        return $stmt->fetch();
    }

    public function getProduitInfo()
    {
        $sql = "SELECT p_pk, p_libelle
                FROM v_paiement_effectue
                WHERE y_reference = " . $this->reference
                ;
        $stmt = $this->db->query($sql);

        return $stmt->fetch();
    }


    public function getPaiements()
    {
        $sql = "SELECT y_status, y_montant, y_date
            FROM v_paiement_effectue
            WHERE y_reference = " . $this->reference
            . " ORDER BY y_date";
        $stmt = $this->db->query($sql);

        return $this->getResults($stmt);
    }
}
