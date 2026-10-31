<?php

namespace App\Repository;

use App\Config\MongoDatabase;
use Exception;

class EvenementRepository
{
    private $collection;

    public function __construct()
    {
        $this->collection = MongoDatabase::getDatabase()->selectCollection('evenements_partie');
    }

    // enregistre une action du joueur (début, réponse, indice, salle validée, fin)
    public function ajouter(int $idPartie, string $type, int $salle, array $details): void
    {
        try {
            $this->collection->insertOne([
                'id_partie' => $idPartie,
                'type' => $type,
                'salle' => $salle,
                'details' => $details,
                'date' => date('Y-m-d H:i:s'),
            ]);
        } catch (Exception $e) {
            // si MongoDB ne répond pas, la partie continue quand même :
            // cet historique sert seulement aux statistiques de l'admin
        }
    }

    // tous les événements d'une partie, dans l'ordre
    public function findByPartie(int $idPartie): array
    {
        $resultat = $this->collection->find(
            ['id_partie' => $idPartie],
            [
                'sort' => ['date' => 1],
                'typeMap' => ['root' => 'array', 'document' => 'array'],
            ]
        );

        return $resultat->toArray();
    }
}
