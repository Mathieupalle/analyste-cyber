<?php

namespace App\Controller;

use App\Security\Auth;
use App\Security\Csrf;
use App\Service\PartieService;

class PartieController
{
    // bouton "Commencer l'enquête"
    public function nouvelle(): void
    {
        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::verifier();

            $partieService = new PartieService();
            $partieService->demarrer($_SESSION['utilisateur']['id']);

            header('Location: /partie');
            exit;
        }

        header('Location: /');
        exit;
    }

    // affiche la salle où se trouve le joueur
    public function salle(): void
    {
        Auth::requireLogin();

        $partieService = new PartieService();
        $partie = $partieService->getPartieEnCours($_SESSION['utilisateur']['id']);

        // pas de partie en cours (temps écoulé ou partie finie)
        if ($partie === null) {
            header('Location: /partie/fin');
            exit;
        }

        $numeroSalle = $partieService->getSalleCourante($partie['id_partie']);
        $enigme = $partieService->getEnigme($numeroSalle);
        $compteurs = $partieService->getCompteurs($partie['id_partie']);
        $secondesRestantes = PartieService::DUREE_MAX - $partie['secondes'];

        $titre = 'Salle ' . $numeroSalle;

        require __DIR__ . '/../../templates/header.php';
        require __DIR__ . '/../../templates/partie/salle.php';
        require __DIR__ . '/../../templates/footer.php';
    }

    // reçoit la réponse envoyée en JavaScript (fetch) et renvoie du JSON
    public function repondre(): void
    {
        Auth::requireLogin();
        Csrf::verifier();

        $reponse = $_POST['reponse'] ?? '';

        $partieService = new PartieService();
        $resultat = $partieService->repondre($_SESSION['utilisateur']['id'], $reponse);

        header('Content-Type: application/json');
        echo json_encode(['resultat' => $resultat]);
    }

    public function fin(): void
    {
        Auth::requireLogin();

        $partieService = new PartieService();
        $partie = $partieService->getResultat($_SESSION['utilisateur']['id']);

        if ($partie === null) {
            header('Location: /');
            exit;
        }

        $titre = 'Fin de partie';

        require __DIR__ . '/../../templates/header.php';
        require __DIR__ . '/../../templates/partie/fin.php';
        require __DIR__ . '/../../templates/footer.php';
    }
}
