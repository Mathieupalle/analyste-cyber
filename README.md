# Analyste cyber

Escape game sur la cybersécurité, réalisé pour mon titre DWWM (Studi).

La PME Calanques Bio (fictive) a été piratée. Le joueur est analyste junior et doit retrouver comment l'attaquant est entré avant la fin du chrono, en passant par 4 salles : la boîte mail, les journaux de connexion, le mot de passe et le rapport.

## Technologies

- PHP 8 (architecture Controller / Service / Repository)
- MySQL et MongoDB
- HTML, CSS, JavaScript
- Docker
- PHPUnit

## Installation en local

```bash
cp .env.example .env
docker compose up -d --build
docker compose exec app composer install
docker compose exec app php database/mongo/import-journaux.php
```

La dernière commande importe les faux journaux de connexion (salle 2) dans MongoDB.

Pour créer un compte administrateur :

```bash
docker compose exec app php database/creer-admin.php pseudo email motdepasse
```

- site : http://localhost:8090
- phpMyAdmin : http://localhost:8091
- tests : `docker compose exec app vendor/bin/phpunit`

Pour remettre la base à zéro : `docker compose down -v` puis `docker compose up -d`

## Conception

- [User stories](docs/user-stories.md)
- [Wireframes](docs/wireframes)
- [Maquettes](docs/maquettes)
- [Fichier Figma](https://www.figma.com/design/vK5Fla58ylnlidmQgRPFe0/Analyste-cyber---Wireframes-et-maquettes)

## Déploiement (Heroku + JawsDB + MongoDB Atlas)

L'application est hébergée sur Heroku (offre Eco), avec JawsDB pour MySQL (offre Kitefin gratuite, 5 Mo) et MongoDB Atlas pour MongoDB (cluster gratuit M0).

### 1. MongoDB Atlas

1. Créer un cluster gratuit (M0) sur https://cloud.mongodb.com
2. Database Access : créer un utilisateur avec un mot de passe
3. Network Access : autoriser `0.0.0.0/0` (l'adresse IP d'Heroku change à chaque redémarrage)
4. Connect > Drivers : copier l'adresse de connexion `mongodb+srv://...`

### 2. Heroku

Il faut avoir installé le Heroku CLI et être connecté (`heroku login`).

```bash
heroku create nom-de-l-application
heroku addons:create jawsdb:kitefin
heroku config:set MONGO_URI="mongodb+srv://utilisateur:motdepasse@cluster.mongodb.net" MONGO_DB=analyste_cyber
heroku config:set MISTRAL_API_KEY=la_cle_mistral
git push heroku main
```

- Le fichier `Procfile` indique à Heroku de servir le site depuis le dossier `public/`.
- JawsDB crée automatiquement la variable `JAWSDB_URL`, lue par `src/Config/Database.php`.
- Les extensions PHP (`pdo_mysql`, `mongodb`) sont installées par Heroku grâce au `composer.json`.

### 3. Installer les données

```bash
heroku run php database/installer-base.php
heroku run php database/mongo/import-journaux.php
heroku run php database/creer-admin.php admin email motdepasse
heroku open
```

### Vérifications après la mise en ligne

- le site est en HTTPS (fourni par Heroku)
- le fichier `.env` n'est pas envoyé (il est dans le `.gitignore`), les mots de passe sont dans les variables d'Heroku (`heroku config`)
- les erreurs PHP ne s'affichent pas aux visiteurs
- sans clé Mistral, les indices de secours s'affichent quand même
