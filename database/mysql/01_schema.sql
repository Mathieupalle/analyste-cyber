-- Création de la base de données Analyste cyber
-- (sur JawsDB la base existe déjà : installer-base.php enlève les 2 lignes suivantes)
CREATE DATABASE IF NOT EXISTS analyste_cyber CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE analyste_cyber;
SET NAMES utf8mb4;

CREATE TABLE role (
    id_role INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(20) NOT NULL UNIQUE
);

-- le mot de passe est stocké avec password_hash()
CREATE TABLE utilisateur (
    id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    pseudo VARCHAR(30) NOT NULL UNIQUE,
    email VARCHAR(180) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    date_inscription DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_role INT NOT NULL,
    FOREIGN KEY (id_role) REFERENCES role(id_role)
);

CREATE TABLE salle (
    id_salle INT AUTO_INCREMENT PRIMARY KEY,
    numero_ordre INT NOT NULL UNIQUE,
    titre VARCHAR(100) NOT NULL,
    description TEXT NOT NULL
);

-- type_reponse : texte, selection (plusieurs choix) ou ordre (glisser-déposer)
-- reponse_attendue reste côté serveur, elle n'est jamais envoyée au navigateur
CREATE TABLE enigme (
    id_enigme INT AUTO_INCREMENT PRIMARY KEY,
    intitule VARCHAR(255) NOT NULL,
    type_reponse VARCHAR(20) NOT NULL,
    reponse_attendue VARCHAR(255) NOT NULL,
    id_salle INT NOT NULL,
    FOREIGN KEY (id_salle) REFERENCES salle(id_salle) ON DELETE CASCADE
);

-- indices écrits à la main, utilisés si l'API Groq ne répond pas
CREATE TABLE indice (
    id_indice INT AUTO_INCREMENT PRIMARY KEY,
    texte VARCHAR(255) NOT NULL,
    ordre INT NOT NULL,
    id_enigme INT NOT NULL,
    FOREIGN KEY (id_enigme) REFERENCES enigme(id_enigme) ON DELETE CASCADE
);

-- statut : en_cours, gagnee, perdue ou abandonnee
CREATE TABLE partie (
    id_partie INT AUTO_INCREMENT PRIMARY KEY,
    date_debut DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_fin DATETIME NULL,
    statut VARCHAR(20) NOT NULL DEFAULT 'en_cours',
    score INT NULL,
    id_utilisateur INT NOT NULL,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur) ON DELETE CASCADE
);

-- chaque réponse envoyée par le joueur (pour compter les erreurs)
CREATE TABLE tentative (
    id_tentative INT AUTO_INCREMENT PRIMARY KEY,
    reponse_donnee VARCHAR(255) NOT NULL,
    est_correcte BOOLEAN NOT NULL,
    date_tentative DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_partie INT NOT NULL,
    id_enigme INT NOT NULL,
    FOREIGN KEY (id_partie) REFERENCES partie(id_partie) ON DELETE CASCADE,
    FOREIGN KEY (id_enigme) REFERENCES enigme(id_enigme)
);

-- chaque indice demandé (source : ia ou secours)
CREATE TABLE demande_indice (
    id_demande INT AUTO_INCREMENT PRIMARY KEY,
    source VARCHAR(10) NOT NULL,
    date_demande DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_partie INT NOT NULL,
    id_enigme INT NOT NULL,
    FOREIGN KEY (id_partie) REFERENCES partie(id_partie) ON DELETE CASCADE,
    FOREIGN KEY (id_enigme) REFERENCES enigme(id_enigme)
);
