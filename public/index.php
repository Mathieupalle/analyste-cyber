<?php

session_start();

require __DIR__ . '/../vendor/autoload.php';

use App\Controller\AccueilController;
use App\Controller\AuthController;

// on récupère l'adresse demandée, sans les paramètres après le ?
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($url) {
    case '/':
        $controller = new AccueilController();
        $controller->index();
        break;

    case '/inscription':
        $controller = new AuthController();
        $controller->inscription();
        break;

    case '/connexion':
        $controller = new AuthController();
        $controller->connexion();
        break;

    case '/deconnexion':
        $controller = new AuthController();
        $controller->deconnexion();
        break;

    default:
        http_response_code(404);
        echo 'Page introuvable';
        break;
}
