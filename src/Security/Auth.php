<?php

namespace App\Security;

class Auth
{
    public static function estConnecte(): bool
    {
        return isset($_SESSION['utilisateur']);
    }

    // page réservée aux utilisateurs connectés
    public static function requireLogin(): void
    {
        if (!self::estConnecte()) {
            header('Location: /connexion');
            exit;
        }
    }

    // page réservée à un rôle (ex : admin)
    public static function requireRole(string $role): void
    {
        self::requireLogin();

        if ($_SESSION['utilisateur']['role'] !== $role) {
            http_response_code(403);
            die('Accès refusé');
        }
    }
}
