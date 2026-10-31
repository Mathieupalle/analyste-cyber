# User stories - Analyste cyber

La PME fictive Calanques Bio a été piratée. Le joueur est analyste junior et doit retrouver comment l'attaquant est entré avant la fin du chrono.

## Règles du jeu

- Une partie dure 20 minutes. Si le temps est écoulé, la partie est perdue et ne compte pas dans le classement.
- Score : on part de 1000 points, -1 par seconde, -50 par indice, -20 par erreur. Le score ne peut pas être négatif.
- Un joueur ne peut avoir qu'une partie en cours. S'il en relance une, l'ancienne est abandonnée.
- Les salles se jouent dans l'ordre : on ne peut pas aller dans une salle si on n'a pas fini la précédente.
- Les réponses sont vérifiées par le serveur, la bonne réponse n'est jamais envoyée au navigateur.

## Visiteur

- **US1** : en tant que visiteur, je veux voir le scénario et les 4 salles sur la page d'accueil pour savoir à quoi je vais jouer.
- **US2** : en tant que visiteur, je veux voir le classement (top 10) pour voir les meilleurs scores.
- **US3** : en tant que visiteur, je veux créer un compte (pseudo, e-mail, mot de passe) pour pouvoir jouer.

## Joueur

- **US4** : en tant que joueur, je veux me connecter et me déconnecter.
- **US5** : en tant que joueur, je veux lancer une partie, ce qui démarre le chrono.
- **US6** : en tant que joueur, je veux résoudre l'énigme de chaque salle :
  - salle 1 : trouver les 3 indices de phishing dans un e-mail
  - salle 2 : trouver l'IP de l'attaquant dans un tableau de journaux qu'on peut filtrer (données dans MongoDB)
  - salle 3 : trouver le mot de passe qui a été deviné
  - salle 4 : remettre les étapes de l'attaque dans l'ordre (glisser-déposer)
- **US7** : en tant que joueur, je veux savoir tout de suite si ma réponse est juste ou fausse, sans recharger la page.
- **US8** : en tant que joueur, je veux pouvoir demander un indice quand je suis bloqué (le mentor IA donne un indice sans donner la réponse, et si l'API ne marche pas, un indice écrit à l'avance s'affiche).
- **US9** : en tant que joueur, je veux voir mon score à la fin avec le détail (temps, indices, erreurs).
- **US10** : en tant que joueur, je veux voir l'historique de mes parties.

## Administrateur

- **US11** : en tant qu'admin, je veux un tableau de bord avec des statistiques (nombre de parties, réussite par salle) sous forme de graphiques.
- **US12** : en tant qu'admin, je veux modifier les énigmes et les indices de secours.
- **US13** : en tant qu'admin, je veux voir la liste des joueurs et pouvoir supprimer un compte (avec ses parties).
- **US14** : en tant qu'admin, je veux voir le déroulé d'une partie (salles ouvertes, réponses, indices) pour savoir où les joueurs bloquent.

## Sécurité

- mot de passe hashé avec `password_hash()` et vérifié avec `password_verify()`
- requêtes préparées avec PDO
- `htmlspecialchars()` sur tout ce qui est affiché
- jeton CSRF sur les formulaires
- pages joueur réservées aux utilisateurs connectés, pages admin réservées au rôle admin
- tout est fictif : pas de vrai lien piégé, pas de vrai e-mail envoyé
