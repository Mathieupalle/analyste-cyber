<?php

namespace App\Config;

use MongoDB\Client;
use MongoDB\Database;

class MongoDatabase
{
    private static $database = null;

    // renvoie la base MongoDB (créée une seule fois)
    public static function getDatabase(): Database
    {
        if (self::$database === null) {
            // on attend 2 secondes maximum si MongoDB ne répond pas
            $client = new Client(getenv('MONGO_URI'), ['serverSelectionTimeoutMS' => 2000]);
            self::$database = $client->selectDatabase(getenv('MONGO_DB'));
        }

        return self::$database;
    }
}
