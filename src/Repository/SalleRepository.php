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

    // admin : nombre de parties qui ont réussi chaque salle
    public function findReussites(): array
    {
        $requete = $this->pdo->query(
            'SELECT s.numero_ordre, s.titre, COUNT(DISTINCT t.id_partie) AS nb_reussites
             FROM salle s
             JOIN enigme e ON e.id_salle = s.id_salle
             LEFT JOIN tentative t ON t.id_enigme = e.id_enigme AND t.est_correcte = 1
             GROUP BY s.id_salle, s.numero_ordre, s.titre
             ORDER BY s.numero_ordre'
        );

        return $requete->fetchAll();
    }
}
