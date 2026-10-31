<?php
// page de test pour vérifier la connexion aux bases
// à remplacer par le routeur

require __DIR__ . '/../vendor/autoload.php';

// test MySQL
try {
    $dsn = 'mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_NAME') . ';charset=utf8mb4';
    $pdo = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASSWORD'));
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $nbSalles = $pdo->query('SELECT COUNT(*) FROM salle')->fetchColumn();
    echo 'MySQL : OK (' . $nbSalles . ' salles)<br>';
} catch (PDOException $e) {
    echo 'MySQL : erreur - ' . $e->getMessage() . '<br>';
}

// test MongoDB
try {
    $client = new MongoDB\Client(getenv('MONGO_URI'));
    $client->selectDatabase(getenv('MONGO_DB'))->command(['ping' => 1]);
    echo 'MongoDB : OK';
} catch (Exception $e) {
    echo 'MongoDB : erreur - ' . $e->getMessage();
}
