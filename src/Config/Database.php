<?php

namespace App\Config;

use PDO;
use PDOException;

class Database
{
    private static $pdo = null;

    // renvoie la connexion à MySQL (créée une seule fois)
    public static function getConnection(): PDO
    {
        if (self::$pdo === null) {
            // en local (Docker) : les variables du fichier .env
            $hote = getenv('DB_HOST');
            $port = getenv('DB_PORT');
            $base = getenv('DB_NAME');
            $utilisateur = getenv('DB_USER');
            $motDePasse = getenv('DB_PASSWORD');

            // sur Heroku : JawsDB donne tout dans une seule adresse
            // mysql://utilisateur:motdepasse@hote:port/base
            if (getenv('JAWSDB_URL')) {
                $url = parse_url(getenv('JAWSDB_URL'));
                $hote = $url['host'];
                $port = $url['port'];
                $base = ltrim($url['path'], '/');
                $utilisateur = $url['user'];
                $motDePasse = $url['pass'];
            }

            $dsn = 'mysql:host=' . $hote . ';port=' . $port . ';dbname=' . $base . ';charset=utf8mb4';

            try {
                self::$pdo = new PDO($dsn, $utilisateur, $motDePasse);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die('Erreur de connexion à la base de données');
            }
        }

        return self::$pdo;
    }
}
