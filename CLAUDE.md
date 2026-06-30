# WiseFarm — CLAUDE.md

Laravel 13 API + Nuxt 4 frontend. Fully containerised with Docker.

## Stack

- **Backend**: Laravel 13, PHP 8.3, PostgreSQL 16, Redis 7 — runs in Docker
- **Frontend**: Nuxt 4, Vue 3, TypeScript, Pinia, Nuxt UI — runs in Docker
- **Auth**: Laravel Sanctum (token-based from API, not SPA cookie)

## Commands

```bash
# From repo root (wisefarm/)
make reset-db        # Full reset: migrate:fresh + seed (destroys all data)
make migrate         # Safe: migrate + seed (preserves existing data)
make seed            # Re-seed only (idempotent, safe to run anytime)
make test            # Run backend tests
make logs            # Stream backend logs
make shell-backend   # Shell into backend container
make shell-frontend  # Shell into frontend container

docker compose restart backend   # Required after ANY .php file change
```

## Demo account

- Email: `demo@wisefarm.com`
- Password: `password`
- Created by `DatabaseSeeder` using `firstOrCreate` (idempotent)

## CRITICAL — Database rules

**NEVER** run `php artisan migrate:fresh` alone. It drops all tables and
the demo user is lost. Always use one of:

```bash
make migrate     # For new migrations (preserves data, re-seeds safely)
make reset-db    # Full reset only when explicitly asked by the user
```

The Makefile enforces this — there is no bare `migrate:fresh` target.

If a migration adds new columns to existing tables, run `make migrate`.
Only run `make reset-db` when the user explicitly says "reset the database"
or "start fresh".

## Container cache

`php artisan serve` caches loaded PHP files in memory. After editing any
`.php` file, run:

```bash
docker compose restart backend
```

Otherwise the container continues to use the old bytecode.

## Routing

All API routes are under `/api/` (proxied from Nginx to the backend container).
Frontend SPA routes are file-based (`app/pages/dashboard/*.vue`).

New Nuxt pages: add file in `frontend/app/pages/`, no registration needed.
New API routes: add to `backend/routes/api.php`, restart backend container.
