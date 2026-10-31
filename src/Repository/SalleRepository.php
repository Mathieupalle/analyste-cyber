<?php

namespace App\Repository;

use App\Config\Database;
use PDO;

class SalleRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    // toutes les salles dans l'ordre du jeu
    public function findAll(): array
    {
        $requete = $this->pdo->query('SELECT id_salle, numero_ordre, titre, description FROM salle ORDER BY numero_ordre');

        return $requete->fetchAll();
    }
}
