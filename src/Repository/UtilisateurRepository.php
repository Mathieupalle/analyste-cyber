<?php

namespace App\Repository;

use App\Config\Database;
use PDO;

class UtilisateurRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    // cherche un utilisateur avec son e-mail (avec le nom de son rôle)
    public function findByEmail(string $email): ?array
    {
        $requete = $this->pdo->prepare(
            'SELECT u.id_utilisateur, u.pseudo, u.email, u.mot_de_passe, r.libelle AS role
             FROM utilisateur u
             JOIN role r ON r.id_role = u.id_role
             WHERE u.email = :email'
        );
        $requete->execute(['email' => $email]);
        $utilisateur = $requete->fetch();

        if ($utilisateur === false) {
            return null;
        }

        return $utilisateur;
    }

    public function pseudoExiste(string $pseudo): bool
    {
        $requete = $this->pdo->prepare('SELECT COUNT(*) FROM utilisateur WHERE pseudo = :pseudo');
        $requete->execute(['pseudo' => $pseudo]);

        return $requete->fetchColumn() > 0;
    }

    public function emailExiste(string $email): bool
    {
        $requete = $this->pdo->prepare('SELECT COUNT(*) FROM utilisateur WHERE email = :email');
        $requete->execute(['email' => $email]);

        return $requete->fetchColumn() > 0;
    }

    // ajoute un joueur (id_role = 1)
    public function create(string $pseudo, string $email, string $motDePasseHash): void
    {
        $requete = $this->pdo->prepare(
            'INSERT INTO utilisateur (pseudo, email, mot_de_passe, id_role)
             VALUES (:pseudo, :email, :mot_de_passe, 1)'
        );
        $requete->execute([
            'pseudo' => $pseudo,
            'email' => $email,
            'mot_de_passe' => $motDePasseHash,
        ]);
    }

    // admin : tous les comptes avec leur rôle et leur nombre de parties
    public function findAll(): array
    {
        $requete = $this->pdo->query(
            'SELECT u.id_utilisateur, u.pseudo, u.email, u.date_inscription, r.libelle AS role,
                    COUNT(p.id_partie) AS nb_parties
             FROM utilisateur u
             JOIN role r ON r.id_role = u.id_role
             LEFT JOIN partie p ON p.id_utilisateur = u.id_utilisateur
             GROUP BY u.id_utilisateur, u.pseudo, u.email, u.date_inscription, r.libelle
             ORDER BY u.date_inscription DESC'
        );

        return $requete->fetchAll();
    }

    // supprime un compte joueur (ses parties sont supprimées grâce au ON DELETE CASCADE)
    // id_role = 1 : on ne peut pas supprimer un admin, même en modifiant le formulaire
    public function delete(int $idUtilisateur): void
    {
        $requete = $this->pdo->prepare('DELETE FROM utilisateur WHERE id_utilisateur = :id_utilisateur AND id_role = 1');
        $requete->execute(['id_utilisateur' => $idUtilisateur]);
    }

    // crée un compte admin (utilisé par le script database/creer-admin.php)
    public function createAdmin(string $pseudo, string $email, string $motDePasseHash): void
    {
        $requete = $this->pdo->prepare(
            'INSERT INTO utilisateur (pseudo, email, mot_de_passe, id_role)
             VALUES (:pseudo, :email, :mot_de_passe, 2)'
        );
        $requete->execute([
            'pseudo' => $pseudo,
            'email' => $email,
            'mot_de_passe' => $motDePasseHash,
        ]);
    }
}
