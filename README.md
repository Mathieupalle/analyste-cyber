# Analyste cyber — Escape game pédagogique

Projet DWWM. Scénario 100 % fictif : la PME « Calanques Bio » a été piratée.

## Installation locale (Docker)

```bash
cp .env.example .env
docker compose up -d --build
docker compose exec app composer install
```

- Application : http://localhost:8080
- phpMyAdmin : http://localhost:8081
- Tests : `docker compose exec app vendor/bin/phpunit`

Réinitialiser la base (relance les scripts SQL) : `docker compose down -v` puis `docker compose up -d`.
