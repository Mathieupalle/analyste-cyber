<?php

namespace App\Controller;

use App\Security\Auth;
use App\Security\Csrf;
use App\Service\AdminService;

class AdminController
{
    public function tableauDeBord(): void
    {
        Auth::requireRole('admin');

        $adminService = new AdminService();
        $chiffres = $adminService->getChiffresCles();
        $reussiteParSalle = $adminService->getReussiteParSalle();
        $partiesParJour = $adminService->getPartiesParJour();
        $dernieresParties = $adminService->getDernieresParties();

        $titre = 'Admin - Tableau de bord';

        require __DIR__ . '/../../templates/header.php';
        require __DIR__ . '/../../templates/admin/tableau-de-bord.php';
        require __DIR__ . '/../../templates/footer.php';
    }

    public function enigmes(): void
    {
        Auth::requireRole('admin');

        $adminService = new AdminService();
        $erreurs = [];
        $succes = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::verifier();

            $erreurs = $adminService->modifierEnigme(
                (int) ($_POST['id_enigme'] ?? 0),
                trim($_POST['intitule'] ?? ''),
                trim($_POST['reponse_attendue'] ?? ''),
                trim($_POST['indice1'] ?? ''),
                trim($_POST['indice2'] ?? '')
            );

            if (empty($erreurs)) {
                $succes = true;
            }
        }

        $enigmes = $adminService->getEnigmes();

        $titre = 'Admin - Enigmes';

        require __DIR__ . '/../../templates/header.php';
        require __DIR__ . '/../../templates/admin/enigmes.php';
        require __DIR__ . '/../../templates/footer.php';
    }

    public function joueurs(): void
    {
        Auth::requireRole('admin');

        $adminService = new AdminService();
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::verifier();

            $idUtilisateur = (int) ($_POST['id_utilisateur'] ?? 0);

            // l'admin ne peut pas supprimer son propre compte
            if ($idUtilisateur === (int) $_SESSION['utilisateur']['id']) {
                $message = 'Tu ne peux pas supprimer ton propre compte.';
            } else {
                $adminService->supprimerJoueur($idUtilisateur);
                $message = 'Le compte a été supprimé.';
            }
        }

        $joueurs = $adminService->getJoueurs();

        $titre = 'Admin - Joueurs';

        require __DIR__ . '/../../templates/header.php';
        require __DIR__ . '/../../templates/admin/joueurs.php';
        require __DIR__ . '/../../templates/footer.php';
    }

    // déroulé d'une partie : /admin/partie?id=12
    public function partie(): void
    {
        Auth::requireRole('admin');

        $adminService = new AdminService();
        $partie = $adminService->getDeroule((int) ($_GET['id'] ?? 0));

        if ($partie === null) {
            header('Location: /admin');
            exit;
        }

        $titre = 'Admin - Déroulé de la partie';

        require __DIR__ . '/../../templates/header.php';
        require __DIR__ . '/../../templates/admin/partie.php';
        require __DIR__ . '/../../templates/footer.php';
    }
}
