<?php

namespace App\Repository;

use App\Config\Database;
use PDO;

class TentativeRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function create(int $idPartie, int $idEnigme, string $reponse, bool $estCorrecte, string $date): void
    {
        $requete = $this->pdo->prepare(
            'INSERT INTO tentative (reponse_donnee, est_correcte, date_tentative, id_partie, id_enigme)
             VALUES (:reponse, :est_correcte, :date_tentative, :id_partie, :id_enigme)'
        );
        $requete->execute([
            'reponse' => $reponse,
            'est_correcte' => $estCorrecte ? 1 : 0,
            'date_tentative' => $date,
            'id_partie' => $idPartie,
            'id_enigme' => $idEnigme,
        ]);
    }

    // nombre de salles réussies dans la partie
    public function compterReussites(int $idPartie): int
    {
        $requete = $this->pdo->prepare(
            'SELECT COUNT(DISTINCT id_enigme) FROM tentative WHERE id_partie = :id_partie AND est_correcte = 1'
        );
        $requete->execute(['id_partie' => $idPartie]);

        return (int) $requete->fetchColumn();
    }

    public function compterErreurs(int $idPartie): int
    {
        $requete = $this->pdo->prepare(
            'SELECT COUNT(*) FROM tentative WHERE id_partie = :id_partie AND est_correcte = 0'
        );
        $requete->execute(['id_partie' => $idPartie]);

        return (int) $requete->fetchColumn();
    }
}
