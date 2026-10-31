<?php

namespace App\Repository;

use App\Config\Database;
use PDO;

class EnigmeRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    // l'énigme d'une salle pour l'affichage : on ne prend PAS la réponse attendue
    public function findBySalle(int $numeroSalle): ?array
    {
        $requete = $this->pdo->prepare(
            'SELECT e.id_enigme, e.intitule, e.type_reponse, s.numero_ordre, s.titre, s.description
             FROM enigme e
             JOIN salle s ON s.id_salle = e.id_salle
             WHERE s.numero_ordre = :numero'
        );
        $requete->execute(['numero' => $numeroSalle]);
        $enigme = $requete->fetch();

        if ($enigme === false) {
            return null;
        }

        return $enigme;
    }

    // la réponse attendue, utilisée seulement pour vérifier côté serveur
    public function findReponse(int $idEnigme): string
    {
        $requete = $this->pdo->prepare('SELECT reponse_attendue FROM enigme WHERE id_enigme = :id_enigme');
        $requete->execute(['id_enigme' => $idEnigme]);

        return $requete->fetchColumn();
    }

    // admin : toutes les énigmes avec leur réponse (page réservée à l'admin)
    public function findAll(): array
    {
        $requete = $this->pdo->query(
            'SELECT e.id_enigme, e.intitule, e.type_reponse, e.reponse_attendue, s.numero_ordre, s.titre
             FROM enigme e
             JOIN salle s ON s.id_salle = e.id_salle
             ORDER BY s.numero_ordre'
        );

        return $requete->fetchAll();
    }

    public function update(int $idEnigme, string $intitule, string $reponseAttendue): void
    {
        $requete = $this->pdo->prepare(
            'UPDATE enigme SET intitule = :intitule, reponse_attendue = :reponse WHERE id_enigme = :id_enigme'
        );
        $requete->execute([
            'intitule' => $intitule,
            'reponse' => $reponseAttendue,
            'id_enigme' => $idEnigme,
        ]);
    }
}
