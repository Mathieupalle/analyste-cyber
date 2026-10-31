<?php
// crée les tables et insère les données de départ dans la base MySQL
// utile sur Heroku (JawsDB) : heroku run php database/installer-base.php
// en local, Docker le fait déjà tout seul au premier démarrage

require __DIR__ . '/../vendor/autoload.php';

use App\Config\Database;

$pdo = Database::getConnection();

foreach (['01_schema.sql', '02_seed.sql'] as $fichier) {
    $sql = file_get_contents(__DIR__ . '/mysql/' . $fichier);

    // sur JawsDB la base existe déjà : on enlève les lignes CREATE DATABASE et USE
    $sql = preg_replace('/^(CREATE DATABASE|USE) .*$/m', '', $sql);

    // on enlève aussi les commentaires (une requête vide provoque une erreur MySQL)
    $sql = preg_replace('/^--.*$/m', '', $sql);

    // on exécute les requêtes une par une (elles finissent par un ;)
    $requetes = explode(";\n", $sql);

    foreach ($requetes as $requete) {
        if (trim($requete) !== '') {
            $pdo->exec($requete);
        }
    }

    echo $fichier . " : OK\n";
}
