-- Données de départ (scénario fictif)
-- les IP 203.0.113.x sont des adresses réservées pour les exemples
USE analyste_cyber;
SET NAMES utf8mb4;

INSERT INTO role (id_role, libelle) VALUES
(1, 'joueur'),
(2, 'admin');

INSERT INTO salle (id_salle, numero_ordre, titre, description) VALUES
(1, 1, 'La boîte mail', 'Un employé de Calanques Bio a reçu un e-mail suspect. Trouve les 3 éléments qui montrent que c''est du phishing.'),
(2, 2, 'Les journaux de connexion', 'Des connexions bizarres ont eu lieu cette nuit. Filtre les journaux et trouve l''adresse IP de l''attaquant.'),
(3, 3, 'Le mot de passe', 'L''attaquant a deviné un mot de passe. Avec les indices trouvés, retrouve lequel.'),
(4, 4, 'Le rapport', 'Remets les étapes de l''attaque dans le bon ordre pour faire ton rapport.');

-- pour selection : les choix dans l'ordre alphabétique, séparés par des virgules
-- pour ordre : les étapes dans le bon ordre, séparées par des virgules
INSERT INTO enigme (id_enigme, intitule, type_reponse, reponse_attendue, id_salle) VALUES
(1, 'Clique sur les 3 éléments qui prouvent que cet e-mail est un faux.', 'selection', 'expediteur,lien,urgence', 1),
(2, 'Quelle adresse IP a servi à l''attaque ?', 'texte', '203.0.113.42', 2),
(3, 'Quel mot de passe l''attaquant a-t-il trouvé ?', 'texte', 'Calanques2026', 3),
(4, 'Remets les étapes de l''attaque dans l''ordre.', 'ordre', 'phishing,force_brute,connexion_reussie,exfiltration', 4);

INSERT INTO indice (texte, ordre, id_enigme) VALUES
('Regarde bien l''adresse de l''expéditeur, lettre par lettre.', 1, 1),
('Passe la souris sur le lien sans cliquer. Et pourquoi il faut répondre aussi vite ?', 2, 1),
('Un attaquant se trompe souvent plusieurs fois avant de réussir.', 1, 2),
('Filtre les connexions en échec et regarde quelle IP revient le plus.', 2, 2),
('Beaucoup de gens mettent le nom de leur entreprise dans leur mot de passe.', 1, 3),
('Nom de l''entreprise + l''année, avec une majuscule.', 2, 3),
('Tout a commencé par un e-mail.', 1, 4),
('Il faut trouver le mot de passe avant de pouvoir se connecter.', 2, 4);

-- compte admin : créer le hash avec
-- php -r "echo password_hash('MonMotDePasse', PASSWORD_DEFAULT);"
-- INSERT INTO utilisateur (pseudo, email, mot_de_passe, id_role)
-- VALUES ('admin', 'admin@calanques-bio.test', 'COLLER_LE_HASH_ICI', 2);
