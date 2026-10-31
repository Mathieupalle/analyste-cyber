<?php

namespace App\Repository;

use App\Config\Database;
use PDO;

class DemandeIndiceRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function compter(int $idPartie): int
    {
        $requete = $this->pdo->prepare('SELECT COUNT(*) FROM demande_indice WHERE id_partie = :id_partie');
        $requete->execute(['id_partie' => $idPartie]);

        return (int) $requete->fetchColumn();
    }
}
