<?php
// importe les faux journaux de connexion dans MongoDB
// à lancer une fois : docker compose exec app php database/mongo/import-journaux.php

require __DIR__ . '/../../vendor/autoload.php';

use App\Config\MongoDatabase;

$journaux = json_decode(file_get_contents(__DIR__ . '/journaux.json'), true);

$collection = MongoDatabase::getDatabase()->selectCollection('journaux_connexion');

// on vide la collection avant d'importer, pour ne pas avoir de doublons
$collection->deleteMany([]);
$collection->insertMany($journaux);

echo count($journaux) . " journaux importés\n";
