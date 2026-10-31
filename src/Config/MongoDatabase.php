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
            $client = new Client(getenv('MONGO_URI'));
            self::$database = $client->selectDatabase(getenv('MONGO_DB'));
        }

        return self::$database;
    }
}
