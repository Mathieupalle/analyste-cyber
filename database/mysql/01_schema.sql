-- =====================================================================
-- Escape game « Analyste cyber » — Création de la base MySQL
-- Projet DWWM — Mathieu Palle
-- =====================================================================
-- En local : on crée la base. Sur JawsDB, la base est imposée :
-- supprimer les 2 lignes ci-dessous et exécuter le reste.
CREATE DATABASE IF NOT EXISTS analyste_cyber CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE analyste_cyber;

-- ---------------------------------------------------------------------
-- Rôles : joueur, admin
-- ---------------------------------------------------------------------
CREATE TABLE role (
    id_role  TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    libelle  VARCHAR(20) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Utilisateurs (joueurs et administrateurs)
-- mot_de_passe : uniquement le hash produit par password_hash()
-- ---------------------------------------------------------------------
CREATE TABLE utilisateur (
    id_utilisateur    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pseudo            VARCHAR(30)  NOT NULL UNIQUE,
    email             VARCHAR(180) NOT NULL UNIQUE,
    mot_de_passe      VARCHAR(255) NOT NULL,
    date_inscription  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_role           TINYINT UNSIGNED NOT NULL,
    CONSTRAINT fk_utilisateur_role FOREIGN KEY (id_role)
        REFERENCES role (id_role)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Salles du jeu, jouées dans l'ordre de numero_ordre (1 à 4)
-- ---------------------------------------------------------------------
CREATE TABLE salle (
    id_salle      TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    numero_ordre  TINYINT UNSIGNED NOT NULL UNIQUE,
    titre         VARCHAR(100) NOT NULL,
    description   TEXT         NOT NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Énigmes : reponse_attendue n'est JAMAIS envoyée au navigateur.
-- Format normalisé selon type_reponse :
--   texte     -> la valeur exacte          ex : 203.0.113.42
--   selection -> identifiants triés A→Z    ex : expediteur,lien,urgence
--   ordre     -> identifiants dans l'ordre ex : phishing,force_brute,...
-- ---------------------------------------------------------------------
CREATE TABLE enigme (
    id_enigme         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    intitule          VARCHAR(255) NOT NULL,
    type_reponse      ENUM('texte', 'selection', 'ordre') NOT NULL,
    reponse_attendue  VARCHAR(255) NOT NULL,
    id_salle          TINYINT UNSIGNED NOT NULL,
    CONSTRAINT fk_enigme_salle FOREIGN KEY (id_salle)
        REFERENCES salle (id_salle) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Indices écrits à la main (secours si l'API Mistral est indisponible)
-- ---------------------------------------------------------------------
CREATE TABLE indice (
    id_indice  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    texte      VARCHAR(255) NOT NULL,
    ordre      TINYINT UNSIGNED NOT NULL,
    id_enigme  INT UNSIGNED NOT NULL,
    CONSTRAINT fk_indice_enigme FOREIGN KEY (id_enigme)
        REFERENCES enigme (id_enigme) ON DELETE CASCADE,
    CONSTRAINT uq_indice_ordre UNIQUE (id_enigme, ordre)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Parties : score calculé par le ScoreService à la fin de la partie
-- ---------------------------------------------------------------------
CREATE TABLE partie (
    id_partie       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    date_debut      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_fin        DATETIME NULL,
    statut          ENUM('en_cours', 'gagnee', 'perdue', 'abandonnee') NOT NULL DEFAULT 'en_cours',
    score           INT UNSIGNED NULL,
    id_utilisateur  INT UNSIGNED NOT NULL,
    CONSTRAINT fk_partie_utilisateur FOREIGN KEY (id_utilisateur)
        REFERENCES utilisateur (id_utilisateur) ON DELETE CASCADE,
    INDEX idx_partie_classement (statut, score)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Tentatives : chaque réponse soumise (sert à compter les erreurs)
-- ---------------------------------------------------------------------
CREATE TABLE tentative (
    id_tentative    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reponse_donnee  VARCHAR(255) NOT NULL,
    est_correcte    BOOLEAN      NOT NULL,
    date_tentative  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_partie       INT UNSIGNED NOT NULL,
    id_enigme       INT UNSIGNED NOT NULL,
    CONSTRAINT fk_tentative_partie FOREIGN KEY (id_partie)
        REFERENCES partie (id_partie) ON DELETE CASCADE,
    CONSTRAINT fk_tentative_enigme FOREIGN KEY (id_enigme)
        REFERENCES enigme (id_enigme) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Demandes d'indice (sert à compter les indices utilisés)
-- source : 'ia' (Mistral) ou 'secours' (table indice)
-- ---------------------------------------------------------------------
CREATE TABLE demande_indice (
    id_demande    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    source        ENUM('ia', 'secours') NOT NULL,
    date_demande  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_partie     INT UNSIGNED NOT NULL,
    id_enigme     INT UNSIGNED NOT NULL,
    CONSTRAINT fk_demande_partie FOREIGN KEY (id_partie)
        REFERENCES partie (id_partie) ON DELETE CASCADE,
    CONSTRAINT fk_demande_enigme FOREIGN KEY (id_enigme)
        REFERENCES enigme (id_enigme) ON DELETE RESTRICT
) ENGINE=InnoDB;
