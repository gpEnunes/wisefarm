# WiseFarm — Farmer Decision Support Assistant

Laravel 13 API + Nuxt 4 SPA monorepo. Portfolio project.

## Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 13, PHP 8.3, PostgreSQL 16, Redis 7 |
| Frontend | Nuxt 4, Vue 3, Pinia, Nuxt UI, TypeScript |
| Auth | Laravel Sanctum (cookie-based SPA auth) |
| Infra | Docker Compose |

## Quick Start

### With Docker

```bash
docker compose up -d
```

### Manual

**Backend**

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

**Frontend**

```bash
cd frontend
npm install
cp .env.example .env
npm run dev
```

## Project Structure

```
wisefarm/
├── backend/      # Laravel API (routes/api.php)
├── frontend/     # Nuxt 4 SPA (app/ source directory)
└── docker-compose.yml
```

## API

Base URL: `http://localhost:8000/api`

| Method | Endpoint | Description |
|---|---|---|
| GET | /ping | Health check |

## Auth

Uses Laravel Sanctum with cookie-based SPA authentication.
Frontend must call `/sanctum/csrf-cookie` before any state-changing request.
