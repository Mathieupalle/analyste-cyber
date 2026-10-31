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

    public function create(int $idPartie, int $idEnigme, string $source, string $date): void
    {
        $requete = $this->pdo->prepare(
            'INSERT INTO demande_indice (source, date_demande, id_partie, id_enigme)
             VALUES (:source, :date_demande, :id_partie, :id_enigme)'
        );
        $requete->execute([
            'source' => $source,
            'date_demande' => $date,
            'id_partie' => $idPartie,
            'id_enigme' => $idEnigme,
        ]);
    }

    // nombre d'indices demandés dans toute la partie
    public function compter(int $idPartie): int
    {
        $requete = $this->pdo->prepare('SELECT COUNT(*) FROM demande_indice WHERE id_partie = :id_partie');
        $requete->execute(['id_partie' => $idPartie]);

        return (int) $requete->fetchColumn();
    }

    // nombre d'indices déjà demandés pour une énigme
    public function compterPourEnigme(int $idPartie, int $idEnigme): int
    {
        $requete = $this->pdo->prepare(
            'SELECT COUNT(*) FROM demande_indice WHERE id_partie = :id_partie AND id_enigme = :id_enigme'
        );
        $requete->execute([
            'id_partie' => $idPartie,
            'id_enigme' => $idEnigme,
        ]);

        return (int) $requete->fetchColumn();
    }

    // admin : nombre total d'indices, et combien viennent du mentor IA
    public function compterTout(): array
    {
        $requete = $this->pdo->query(
            "SELECT COUNT(*) AS total, SUM(CASE WHEN source = 'ia' THEN 1 ELSE 0 END) AS ia FROM demande_indice"
        );

        return $requete->fetch();
    }
}
