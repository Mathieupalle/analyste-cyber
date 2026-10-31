<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Controller\AccueilController;

// on récupère l'adresse demandée, sans les paramètres après le ?
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($url) {
    case '/':
        $controller = new AccueilController();
        $controller->index();
        break;

    default:
        http_response_code(404);
        echo 'Page introuvable';
        break;
}
