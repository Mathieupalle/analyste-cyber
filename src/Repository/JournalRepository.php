<?php

namespace App\Repository;

use App\Config\MongoDatabase;

class JournalRepository
{
    private $collection;

    public function __construct()
    {
        $this->collection = MongoDatabase::getDatabase()->selectCollection('journaux_connexion');
    }

    // tous les journaux de connexion, du plus ancien au plus récent
    public function findAll(): array
    {
        $resultat = $this->collection->find(
            [],
            [
                'sort' => ['date' => 1],
                'typeMap' => ['root' => 'array', 'document' => 'array'],
            ]
        );

        return $resultat->toArray();
    }
}
