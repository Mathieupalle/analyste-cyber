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
            $dsn = 'mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_NAME') . ';charset=utf8mb4';

            try {
                self::$pdo = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASSWORD'));
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die('Erreur de connexion à la base de données');
            }
        }

        return self::$pdo;
    }
}
