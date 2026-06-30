.PHONY: reset-db logs test shell-backend shell-frontend

reset-db:
	docker compose exec backend php artisan migrate:fresh --seed

logs:
	docker compose logs -f backend

test:
	docker compose exec backend php artisan test

shell-backend:
	docker compose exec backend bash

shell-frontend:
	docker compose exec frontend sh
