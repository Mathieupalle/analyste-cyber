# Analyste cyber — Escape game

Projet STUDI DWWM - Dossier Professionnel. Scénario 100 % fictif : la PME « Calanques Bio » a été piratée.

## Installation locale (Docker)

```bash
cp .env.example .env
docker compose up -d --build
docker compose exec app composer install
```

- Application : http://localhost:8090
- phpMyAdmin : http://localhost:8091
- Tests : `docker compose exec app vendor/bin/phpunit`

Réinitialiser la base (relance les scripts SQL) : `docker compose down -v` puis `docker compose up -d`.

## Conception

- User stories : [docs/user-stories.md](docs/user-stories.md)
- Wireframes : [docs/wireframes](docs/wireframes)
- Maquettes : [docs/maquettes](docs/maquettes)
- Fichier Figma : [Analyste cyber — WF et Maquettes](https://www.figma.com/design/rRX935ecQP3x8g8edA65Y9/Analyste-cyber---WF-et-Maquettes)
