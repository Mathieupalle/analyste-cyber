<?php

session_start();

require __DIR__ . '/../vendor/autoload.php';

use App\Controller\AccueilController;
use App\Controller\AuthController;
use App\Controller\PartieController;

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

    case '/partie/nouvelle':
        $controller = new PartieController();
        $controller->nouvelle();
        break;

    case '/partie':
        $controller = new PartieController();
        $controller->salle();
        break;

    case '/partie/repondre':
        $controller = new PartieController();
        $controller->repondre();
        break;

    case '/partie/fin':
        $controller = new PartieController();
        $controller->fin();
        break;

    default:
        http_response_code(404);
        echo 'Page introuvable';
        break;
}
