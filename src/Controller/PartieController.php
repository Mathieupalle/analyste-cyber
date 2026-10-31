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

        // la salle 2 a besoin des journaux de connexion
        $journaux = [];
        if ($numeroSalle === 2) {
            $journaux = $partieService->getJournaux();
        }

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

    // bouton "Demander un indice" (appelé en JavaScript avec fetch)
    public function indice(): void
    {
        Auth::requireLogin();
        Csrf::verifier();

        $partieService = new PartieService();
        $resultat = $partieService->demanderIndice($_SESSION['utilisateur']['id']);

        header('Content-Type: application/json');
        echo json_encode($resultat);
    }

    // page "Mes parties"
    public function historique(): void
    {
        Auth::requireLogin();

        $partieService = new PartieService();
        $parties = $partieService->getHistorique($_SESSION['utilisateur']['id']);

        $titre = 'Mes parties';

        require __DIR__ . '/../../templates/header.php';
        require __DIR__ . '/../../templates/partie/historique.php';
        require __DIR__ . '/../../templates/footer.php';
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
