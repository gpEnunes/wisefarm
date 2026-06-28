# WiseFarm

> Farmer Decision Support Assistant — portfolio project

A full-stack web application that helps farmers manage their land, track crop plantations, and make data-driven decisions.

Built with **Laravel 13** (REST API) + **Nuxt 4** (SPA), fully containerised with Docker.

---

## Features

- Farm and field management
- Crop plantation tracking with harvest forecasting
- Yield recording per plantation
- Multi-user with full data isolation
- Cookie-based authentication (Laravel Sanctum)

## Tech Stack

| Layer | Technology |
|---|---|
| Backend API | Laravel 13, PHP 8.3 |
| Frontend | Nuxt 4, Vue 3, TypeScript |
| State management | Pinia |
| UI library | Nuxt UI |
| Auth | Laravel Sanctum (SPA) |
| Database | PostgreSQL 16 |
| Cache & Queues | Redis 7 |
| Infrastructure | Docker + Docker Compose |

## Getting Started

**Prerequisites:** Docker and Docker Compose only.

```bash
# Clone
git clone https://github.com/gpEnunes/wisefarm.git
cd wisefarm

# Start all services (backend + frontend + postgres + redis)
docker compose up -d

# Run migrations (first time only)
docker compose exec backend php artisan migrate
```

App is available at:
- Frontend: http://localhost:3000
- API: http://localhost:8000/api
- Health check: http://localhost:8000/api/ping

## Development

Changes to `backend/` and `frontend/` are reflected live via Docker volume mounts.

```bash
# Backend logs
docker compose logs -f backend

# Run artisan commands
docker compose exec backend php artisan migrate

# Run tests
docker compose exec backend php artisan test

# Frontend logs
docker compose logs -f frontend
```

## Documentation

- [API Reference](docs/api.md)
- [Architecture](docs/architecture.md)

## Project Structure

```
wisefarm/
├── backend/          # Laravel 13 API
│   ├── app/
│   ├── routes/api.php
│   └── Dockerfile
├── frontend/         # Nuxt 4 SPA
│   ├── app/pages/
│   ├── app/composables/
│   └── Dockerfile
├── docs/
│   ├── api.md        # API reference
│   └── architecture.md
└── docker-compose.yml
```

## License

MIT
