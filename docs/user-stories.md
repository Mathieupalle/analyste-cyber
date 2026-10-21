# User stories — Analyste cyber

Escape game pédagogique. Scénario fictif : la PME « Calanques Bio » a été piratée ; le joueur, analyste junior, doit retrouver comment l'attaquant est entré avant la fin du chrono.

## Règles métier

| Règle | Valeur |
|---|---|
| Durée d'une partie | 20 minutes. Une fois le temps écoulé, la partie est perdue et n'entre pas au classement. |
| Calcul du score | 1 000 points − 1 point par seconde écoulée − 50 points par indice − 20 points par erreur, avec un minimum de 0. |
| Parties simultanées | Une seule partie en cours par joueur. En relancer une abandonne la précédente. |
| Progression | On n'accède à une salle qu'après avoir validé la précédente (vérifié côté serveur). |
| Réponses | Validées uniquement côté serveur. La bonne réponse n'est jamais envoyée au navigateur. |

## Visiteur (non connecté)

**US1** — En tant que visiteur, je veux découvrir le scénario et les 4 salles sur la page d'accueil, afin de savoir à quoi je vais jouer.
- Les salles (titre et description) sont lues dans la base, dans l'ordre de jeu.
- Aucune réponse ni aucun indice n'est affiché.

**US2** — En tant que visiteur, je veux consulter le classement, afin de voir les meilleurs scores avant de m'inscrire.
- Les 10 meilleures parties gagnées : pseudo, score et durée.

**US3** — En tant que visiteur, je veux créer un compte, afin de pouvoir jouer.
- Pseudo et e-mail uniques, mot de passe stocké avec `password_hash`.
- Formulaire protégé par un jeton CSRF, avec messages d'erreur clairs.

## Joueur

**US4** — En tant que joueur, je veux me connecter et me déconnecter, afin d'accéder à mon espace.
- L'identifiant de session est régénéré à la connexion.
- Message d'erreur identique, que l'e-mail ou le mot de passe soit faux.

**US5** — En tant que joueur, je veux lancer une partie, afin de démarrer l'enquête et le chrono.
- L'heure de début est enregistrée côté serveur (le chrono affiché en JS n'est qu'un affichage).

**US6** — En tant que joueur, je veux résoudre l'énigme de chaque salle, afin d'avancer dans l'enquête.
- Salle 1 : repérer l'e-mail de phishing et cliquer sur ses 3 indices.
- Salle 2 : trouver l'IP suspecte dans un tableau de journaux filtrable (données MongoDB).
- Salle 3 : retrouver le mot de passe deviné.
- Salle 4 : remettre les étapes de l'attaque dans l'ordre (glisser-déposer).
- Accéder directement à l'URL d'une salle non débloquée redirige vers la salle en cours.

**US7** — En tant que joueur, je veux savoir immédiatement si ma réponse est juste, afin de corriger mon raisonnement.
- Envoi en `fetch`, sans rechargement de la page.
- Chaque tentative est enregistrée ; une réponse fausse compte comme une erreur.

**US8** — En tant que joueur, je veux demander un indice, afin de ne pas rester bloqué.
- La pénalité est annoncée avant la confirmation.
- L'indice est rédigé par le mentor IA (Mistral, appelé côté serveur) sans jamais révéler la réponse.
- Si l'API est indisponible, un indice de secours écrit à la main est affiché à la place.

**US9** — En tant que joueur, je veux voir mon score détaillé en fin de partie, afin de comprendre comment il est calculé.
- Affichage du temps, du nombre d'indices, du nombre d'erreurs et du score final.

**US10** — En tant que joueur, je veux consulter l'historique de mes parties, afin de suivre ma progression.
- Liste de mes parties : date, statut, score.

## Administrateur

**US11** — En tant qu'admin, je veux un tableau de bord, afin d'ajuster la difficulté du jeu.
- Nombre de parties, taux de réussite par salle, salle la plus difficile (graphiques Chart.js).

**US12** — En tant qu'admin, je veux modifier les énigmes et les indices de secours, afin de faire évoluer le jeu sans toucher au code.

**US13** — En tant qu'admin, je veux lister et supprimer des comptes joueurs, afin de modérer le classement et de répondre à une demande de suppression (RGPD).
- La suppression d'un compte supprime aussi ses parties.

**US14** — En tant qu'admin, je veux consulter le déroulé détaillé d'une partie, afin d'analyser où les joueurs bloquent.
- Chronologie des événements lue dans MongoDB : salle ouverte, réponse, indice demandé, salle validée.

## Sécurité transversale

- Toutes les pages joueur exigent d'être connecté ; les pages admin exigent le rôle admin.
- Tous les formulaires et appels `fetch` qui modifient des données vérifient un jeton CSRF.
- Toutes les requêtes SQL sont préparées (PDO) ; tout affichage est échappé (`htmlspecialchars`).
- Contenu 100 % fictif : aucun vrai lien piégé, aucun e-mail réellement envoyé, IP de la plage de documentation 203.0.113.0/24.
