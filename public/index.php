<?php

session_start([
    'cookie_httponly' => true, // le cookie de session n'est pas lisible en JavaScript
    'cookie_samesite' => 'Lax', // il n'est pas envoyé depuis un autre site (protection en plus du CSRF)
]);

require __DIR__ . '/../vendor/autoload.php';

use App\Controller\AccueilController;
use App\Controller\AdminController;
use App\Controller\AuthController;
use App\Controller\ClassementController;
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

    case '/classement':
        $controller = new ClassementController();
        $controller->index();
        break;

    case '/mes-parties':
        $controller = new PartieController();
        $controller->historique();
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

    case '/partie/indice':
        $controller = new PartieController();
        $controller->indice();
        break;

    case '/partie/fin':
        $controller = new PartieController();
        $controller->fin();
        break;

    case '/admin':
        $controller = new AdminController();
        $controller->tableauDeBord();
        break;

    case '/admin/enigmes':
        $controller = new AdminController();
        $controller->enigmes();
        break;

    case '/admin/joueurs':
        $controller = new AdminController();
        $controller->joueurs();
        break;

    case '/admin/partie':
        $controller = new AdminController();
        $controller->partie();
        break;

    default:
        http_response_code(404);
        echo 'Page introuvable';
        break;
}
