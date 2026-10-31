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
```

- site : http://localhost:8090
- phpMyAdmin : http://localhost:8091
- tests : `docker compose exec app vendor/bin/phpunit`

Pour remettre la base à zéro : `docker compose down -v` puis `docker compose up -d`

## Conception

- [User stories](docs/user-stories.md)
- [Wireframes](docs/wireframes)
- [Maquettes](docs/maquettes)
- [Fichier Figma](https://www.figma.com/design/rRX935ecQP3x8g8edA65Y9/Analyste-cyber---WF-et-Maquettes)
