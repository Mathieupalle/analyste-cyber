-- =====================================================================
-- Escape game « Analyste cyber » — Jeu de données
-- Scénario fictif : la PME « Calanques Bio » a été piratée.
-- Les IP utilisent la plage 203.0.113.0/24, réservée à la documentation
-- (RFC 5737) : aucune vraie machine n'est visée.
-- =====================================================================
USE analyste_cyber;

INSERT INTO role (id_role, libelle) VALUES
    (1, 'joueur'),
    (2, 'admin');

INSERT INTO salle (id_salle, numero_ordre, titre, description) VALUES
    (1, 1, 'La boîte mail',
     'Un employé de Calanques Bio a reçu un e-mail suspect. Repère-le et clique sur les 3 indices qui trahissent le phishing.'),
    (2, 2, 'Les journaux de connexion',
     'Des connexions inhabituelles ont été enregistrées. Filtre les journaux et trouve l''adresse IP de l''attaquant.'),
    (3, 3, 'Le mot de passe',
     'L''attaquant a deviné un mot de passe. À partir des indices récoltés, retrouve lequel.'),
    (4, 4, 'Le rapport',
     'Remets les étapes de l''attaque dans le bon ordre pour rédiger ton rapport d''incident.');

INSERT INTO enigme (id_enigme, intitule, type_reponse, reponse_attendue, id_salle) VALUES
    (1, 'Clique sur les 3 éléments qui prouvent que cet e-mail est frauduleux.',
        'selection', 'expediteur,lien,urgence', 1),
    (2, 'Quelle adresse IP est à l''origine de l''intrusion ?',
        'texte', '203.0.113.42', 2),
    (3, 'Quel mot de passe l''attaquant a-t-il deviné ?',
        'texte', 'Calanques2026', 3),
    (4, 'Remets les étapes de l''attaque dans l''ordre chronologique.',
        'ordre', 'phishing,force_brute,connexion_reussie,exfiltration', 4);

INSERT INTO indice (texte, ordre, id_enigme) VALUES
    ('Regarde attentivement l''adresse de l''expéditeur, lettre par lettre.', 1, 1),
    ('Survole le lien sans cliquer : où mène-t-il vraiment ? Et pourquoi tant de précipitation ?', 2, 1),
    ('Un attaquant échoue souvent plusieurs fois avant de réussir.', 1, 2),
    ('Filtre les connexions échouées et regarde quelle IP revient le plus.', 2, 2),
    ('Beaucoup de gens utilisent le nom de leur entreprise dans leur mot de passe.', 1, 3),
    ('Nom de l''entreprise + année en cours, avec une majuscule.', 2, 3),
    ('Tout a commencé dans la boîte mail.', 1, 4),
    ('L''attaquant a dû deviner le mot de passe avant de pouvoir se connecter.', 2, 4);

-- Compte administrateur : ne jamais écrire un mot de passe en clair ici.
-- Génère le hash avec :  php -r "echo password_hash('TonMotDePasse', PASSWORD_DEFAULT);"
-- puis remplace <HASH> ci-dessous avant d'exécuter la requête.
-- INSERT INTO utilisateur (pseudo, email, mot_de_passe, id_role)
-- VALUES ('admin', 'admin@calanques-bio.test', '<HASH>', 2);
