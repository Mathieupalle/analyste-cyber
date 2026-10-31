<?php

namespace App\Security;

class Csrf
{
    // crée le jeton une fois par session
    public static function getToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    // compare le jeton du formulaire avec celui de la session
    public static function verifier(): void
    {
        $token = $_POST['csrf_token'] ?? '';

        if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            http_response_code(403);
            die('Action non autorisée');
        }
    }
}
