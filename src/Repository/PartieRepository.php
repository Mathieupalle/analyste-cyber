<?php

namespace App\Repository;

use App\Config\Database;
use PDO;

class PartieRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    // crée une partie et renvoie son id
    public function create(int $idUtilisateur, string $dateDebut): int
    {
        $requete = $this->pdo->prepare(
            "INSERT INTO partie (date_debut, statut, id_utilisateur) VALUES (:date_debut, 'en_cours', :id_utilisateur)"
        );
        $requete->execute([
            'date_debut' => $dateDebut,
            'id_utilisateur' => $idUtilisateur,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    // la partie en cours du joueur (ou null s'il n'en a pas)
    public function findEnCours(int $idUtilisateur): ?array
    {
        $requete = $this->pdo->prepare(
            "SELECT id_partie, date_debut FROM partie WHERE id_utilisateur = :id_utilisateur AND statut = 'en_cours'"
        );
        $requete->execute(['id_utilisateur' => $idUtilisateur]);
        $partie = $requete->fetch();

        if ($partie === false) {
            return null;
        }

        return $partie;
    }

    // la dernière partie gagnée ou perdue du joueur
    public function findDerniere(int $idUtilisateur): ?array
    {
        $requete = $this->pdo->prepare(
            "SELECT id_partie, date_debut, date_fin, statut, score FROM partie
             WHERE id_utilisateur = :id_utilisateur AND statut IN ('gagnee', 'perdue')
             ORDER BY id_partie DESC LIMIT 1"
        );
        $requete->execute(['id_utilisateur' => $idUtilisateur]);
        $partie = $requete->fetch();

        if ($partie === false) {
            return null;
        }

        return $partie;
    }

    // les meilleures parties gagnées, tous joueurs confondus
    public function findClassement(int $limite): array
    {
        $requete = $this->pdo->prepare(
            "SELECT u.pseudo, p.score, p.date_debut, p.date_fin
             FROM partie p
             JOIN utilisateur u ON u.id_utilisateur = p.id_utilisateur
             WHERE p.statut = 'gagnee'
             ORDER BY p.score DESC
             LIMIT :limite"
        );
        // LIMIT attend un nombre, on le précise à PDO
        $requete->bindValue('limite', $limite, PDO::PARAM_INT);
        $requete->execute();

        return $requete->fetchAll();
    }

    // toutes les parties d'un joueur, de la plus récente à la plus ancienne
    public function findByUtilisateur(int $idUtilisateur): array
    {
        $requete = $this->pdo->prepare(
            "SELECT id_partie, date_debut, date_fin, statut, score FROM partie
             WHERE id_utilisateur = :id_utilisateur AND statut != 'en_cours'
             ORDER BY id_partie DESC"
        );
        $requete->execute(['id_utilisateur' => $idUtilisateur]);

        return $requete->fetchAll();
    }

    // abandonne la partie en cours quand le joueur en relance une
    public function abandonnerEnCours(int $idUtilisateur, string $dateFin): void
    {
        $requete = $this->pdo->prepare(
            "UPDATE partie SET statut = 'abandonnee', date_fin = :date_fin
             WHERE id_utilisateur = :id_utilisateur AND statut = 'en_cours'"
        );
        $requete->execute([
            'date_fin' => $dateFin,
            'id_utilisateur' => $idUtilisateur,
        ]);
    }

    public function terminer(int $idPartie, string $statut, ?int $score, string $dateFin): void
    {
        $requete = $this->pdo->prepare(
            'UPDATE partie SET statut = :statut, score = :score, date_fin = :date_fin WHERE id_partie = :id_partie'
        );
        $requete->execute([
            'statut' => $statut,
            'score' => $score,
            'date_fin' => $dateFin,
            'id_partie' => $idPartie,
        ]);
    }
}
