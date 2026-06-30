.PHONY: migrate seed reset-db test logs shell-backend shell-frontend

# ──────────────────────────────────────────────────────────────────────────────
# Database
# ──────────────────────────────────────────────────────────────────────────────

# Run pending migrations and re-seed (idempotent — preserves existing data).
# Use this after adding new migration files.
migrate:
	docker compose exec backend php artisan migrate
	docker compose exec backend php artisan db:seed

# Re-seed only, without touching schema (idempotent — safe to run anytime).
# Use this to restore the demo account without touching any table structure.
seed:
	docker compose exec backend php artisan db:seed

# FULL RESET — drops all tables, recreates schema, and seeds.
# Use ONLY when explicitly asked for a full database reset.
# WARNING: all data is permanently lost.
reset-db:
	docker compose exec backend php artisan migrate:fresh --seed

# ──────────────────────────────────────────────────────────────────────────────
# Development
# ──────────────────────────────────────────────────────────────────────────────

test:
	docker compose exec backend php artisan test

logs:
	docker compose logs -f backend

shell-backend:
	docker compose exec backend bash

shell-frontend:
	docker compose exec frontend sh
