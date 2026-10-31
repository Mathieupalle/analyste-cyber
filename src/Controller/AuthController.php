<?php

namespace App\Controller;

use App\Security\Csrf;
use App\Service\AuthService;

class AuthController
{
    public function inscription(): void
    {
        $erreurs = [];
        $pseudo = '';
        $email = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::verifier();

            $pseudo = trim($_POST['pseudo'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $motDePasse = $_POST['mot_de_passe'] ?? '';
            $confirmation = $_POST['confirmation'] ?? '';
            $accepte = isset($_POST['accepte']);

            $authService = new AuthService();
            $erreurs = $authService->inscrire($pseudo, $email, $motDePasse, $confirmation, $accepte);

            if (empty($erreurs)) {
                header('Location: /connexion?inscription=ok');
                exit;
            }
        }

        $titre = 'Inscription';

        require __DIR__ . '/../../templates/header.php';
        require __DIR__ . '/../../templates/inscription.php';
        require __DIR__ . '/../../templates/footer.php';
    }

    public function connexion(): void
    {
        $erreur = '';
        $email = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::verifier();

            $email = trim($_POST['email'] ?? '');
            $motDePasse = $_POST['mot_de_passe'] ?? '';

            $authService = new AuthService();
            $utilisateur = $authService->connecter($email, $motDePasse);

            if ($utilisateur === null) {
                // même message si l'e-mail ou le mot de passe est faux
                $erreur = 'E-mail ou mot de passe incorrect.';
            } else {
                // nouvel identifiant de session après la connexion
                session_regenerate_id(true);

                $_SESSION['utilisateur'] = [
                    'id' => $utilisateur['id_utilisateur'],
                    'pseudo' => $utilisateur['pseudo'],
                    'role' => $utilisateur['role'],
                ];

                header('Location: /');
                exit;
            }
        }

        $titre = 'Connexion';

        require __DIR__ . '/../../templates/header.php';
        require __DIR__ . '/../../templates/connexion.php';
        require __DIR__ . '/../../templates/footer.php';
    }

    public function deconnexion(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::verifier();

            $_SESSION = [];
            session_destroy();
        }

        header('Location: /');
        exit;
    }
}
