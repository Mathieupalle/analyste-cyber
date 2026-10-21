<?php
// PAGE DE VÉRIFICATION TEMPORAIRE : à remplacer par ton routeur.
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

header('Content-Type: text/plain; charset=utf-8');

try {
    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        getenv('DB_HOST'), getenv('DB_PORT'), getenv('DB_NAME'));
    $pdo = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASSWORD'), [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    $nb = $pdo->query('SELECT COUNT(*) FROM salle')->fetchColumn();
    echo "MySQL : OK ($nb salles)\n";
} catch (Throwable $e) {
    echo "MySQL : ERREUR - " . $e->getMessage() . "\n";
}

try {
    $client = new MongoDB\Client(getenv('MONGO_URI'));
    $client->selectDatabase(getenv('MONGO_DB'))->command(['ping' => 1]);
    echo "MongoDB : OK\n";
} catch (Throwable $e) {
    echo "MongoDB : ERREUR - " . $e->getMessage() . "\n";
}
