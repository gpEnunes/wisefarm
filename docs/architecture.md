# WiseFarm — Architecture

## Overview

WiseFarm is a decoupled monorepo: a Laravel 13 REST API consumed by a Nuxt 4 SPA.

```
+-----------------+     HTTP/JSON     +------------------+
|   Nuxt 4 SPA   | ----------------► |  Laravel 13 API  |
|   (port 3000)   |                   |   (port 8000)    |
+-----------------+                   +------------------+
                                              |
                          +-------------------+-------------------+
                          |                                       |
                   +------+------+                       +-------+------+
                   | PostgreSQL  |                       |    Redis     |
                   |   (5432)    |                       |   (6379)     |
                   +-------------+                       +--------------+
```

## Domain Model

```
User
 └── Farm (1..n)
      └── Field (1..n)
           └── Plantation (1..n)
                └── Crop (reference)
```

## Backend (Laravel 13)

- **Auth**: Laravel Sanctum — cookie-based SPA auth
- **DB**: PostgreSQL 16 via Eloquent ORM
- **Cache/Queue**: Redis 7
- **API versioning**: `/api/v1/` (planned)
- **Authorization**: Policies per model

## Frontend (Nuxt 4)

- **State**: Pinia stores
- **HTTP**: native `$fetch` via `useApi` composable
- **UI**: Nuxt UI
- **Charts**: Chart.js + vue-chartjs (planned)

## Infrastructure

All services run via Docker Compose. No local dependencies required beyond Docker.

| Service    | Image              | Port |
|------------|--------------------|------|
| backend    | custom (PHP 8.3)   | 8000 |
| frontend   | custom (Node 20)   | 3000 |
| postgres   | postgres:16-alpine | 5432 |
| redis      | redis:7-alpine     | 6379 |
