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
            } else {
                return false;
            } // pas de résultat
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

    public function update(array $data)
    {
        $sql = "UPDATE produits SET"
            . " p_montant = " . $this->db->quote($data['montant'])
            . ", p_open = " . $this->db->quote($data['open'])
            . ", p_type_paiement = " . $this->db->quote($data['type_paiement'])
            . " WHERE p_pk = " . $this->db->quote($data['id']);

        $res = $this->db->exec($sql);

        if ($res === false) {
            parent::logError('Erreur dans la requête: '
                . PHP_EOL
                . print_r($this->db->errorInfo(), true),
                'alert');
        }

        return $res;
    }

    public function create(array $data)
    {
        $sql = "INSERT INTO produits
            (p_libelle, p_montant, p_open, p_salt, p_type_paiement)
            VALUES ("
            . $this->db->quote($data['libelle']) . ","
            . $this->db->quote($data['montant']) . ","
            . $this->db->quote($data['open']) . ","
            . $this->db->quote('ENVA') . ","
            . $this->db->quote($data['type_paiement']) . ")"
            ;

        $res = $this->db->exec($sql);

        if ($res === false) {
            parent::logError('Erreur dans la requête: '
                . PHP_EOL
                . print_r($this->db->errorInfo(), true),
                'alert');

            return false;
        } else {
            return $this->db->lastInsertId();
        }
    }
}
