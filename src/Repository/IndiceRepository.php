<?php

namespace App\Repository;

use App\Config\Database;
use PDO;

class IndiceRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    // l'indice de secours n°1 ou n°2 d'une énigme
    public function findTexte(int $idEnigme, int $ordre): ?string
    {
        $requete = $this->pdo->prepare('SELECT texte FROM indice WHERE id_enigme = :id_enigme AND ordre = :ordre');
        $requete->execute([
            'id_enigme' => $idEnigme,
            'ordre' => $ordre,
        ]);
        $texte = $requete->fetchColumn();

        if ($texte === false) {
            return null;
        }

        return $texte;
    }
}
