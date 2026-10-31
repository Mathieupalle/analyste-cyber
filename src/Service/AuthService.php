<?php

namespace App\Service;

use App\Repository\UtilisateurRepository;

class AuthService
{
    private UtilisateurRepository $utilisateurRepository;

    public function __construct()
    {
        $this->utilisateurRepository = new UtilisateurRepository();
    }

    // vérifie le formulaire d'inscription et crée le compte
    // renvoie la liste des erreurs (vide si tout est bon)
    public function inscrire(string $pseudo, string $email, string $motDePasse, string $confirmation, bool $accepte): array
    {
        $erreurs = [];

        if (mb_strlen($pseudo) < 3 || mb_strlen($pseudo) > 30) {
            $erreurs[] = 'Le pseudo doit faire entre 3 et 30 caractères.';
        } elseif ($this->utilisateurRepository->pseudoExiste($pseudo)) {
            $erreurs[] = 'Ce pseudo est déjà pris.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreurs[] = 'L\'adresse e-mail n\'est pas valide.';
        } elseif ($this->utilisateurRepository->emailExiste($email)) {
            $erreurs[] = 'Cette adresse e-mail est déjà utilisée.';
        }

        if (strlen($motDePasse) < 8) {
            $erreurs[] = 'Le mot de passe doit faire au moins 8 caractères.';
        }

        if ($motDePasse !== $confirmation) {
            $erreurs[] = 'Les deux mots de passe ne sont pas identiques.';
        }

        if (!$accepte) {
            $erreurs[] = 'Tu dois accepter la politique de confidentialité.';
        }

        if (empty($erreurs)) {
            $hash = password_hash($motDePasse, PASSWORD_DEFAULT);
            $this->utilisateurRepository->create($pseudo, $email, $hash);
        }

        return $erreurs;
    }

    // renvoie l'utilisateur si l'e-mail et le mot de passe sont bons, sinon null
    public function connecter(string $email, string $motDePasse): ?array
    {
        $utilisateur = $this->utilisateurRepository->findByEmail($email);

        if ($utilisateur === null) {
            return null;
        }

        if (!password_verify($motDePasse, $utilisateur['mot_de_passe'])) {
            return null;
        }

        return $utilisateur;
    }
}
