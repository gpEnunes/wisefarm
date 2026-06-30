# WiseFarm API Documentation

Base URL: `http://localhost:8000/api`

All endpoints return JSON. Protected endpoints require a Bearer token in the `Authorization` header.

---

## Authentication

WiseFarm uses Laravel Sanctum with **token-based** authentication. On successful login or registration the API returns a plain-text API token. The frontend stores this token (in `localStorage` via Pinia) and attaches it to every subsequent request as:

```
Authorization: Bearer <token>
```

No CSRF cookie exchange is needed — Sanctum issues tokens directly.

### Flow

1. `POST /api/auth/register` or `POST /api/auth/login` — returns `token` in the response body
2. Store the token client-side
3. Include `Authorization: Bearer <token>` on all protected requests
4. `POST /api/auth/logout` — invalidates the current token server-side

---

## Endpoints

### Health

#### GET /api/ping

Health check. No authentication required.

**Response 200:**
```json
{
  "status": "ok",
  "project": "WiseFarm"
}
```

---

### Auth

#### POST /api/auth/register

Register a new user.

**Request body:**
```json
{
  "name": "João Silva",
  "email": "joao@example.com",
  "password": "password",
  "password_confirmation": "password"
}
```

**Response 201:**
```json
{
  "message": "Account created successfully.",
  "token": "1|abc123...",
  "user": {
    "id": 1,
    "name": "João Silva",
    "email": "joao@example.com"
  }
}
```

#### POST /api/auth/login

**Request body:**
```json
{
  "email": "joao@example.com",
  "password": "password"
}
```

**Response 200:**
```json
{
  "message": "Authenticated.",
  "token": "1|abc123...",
  "user": {
    "id": 1,
    "name": "João Silva",
    "email": "joao@example.com"
  }
}
```

**Response 422 (invalid credentials):**
```json
{
  "message": "The provided credentials are incorrect."
}
```

#### POST /api/auth/logout

Requires authentication.

**Response 200:**
```json
{
  "message": "Logged out."
}
```

#### GET /api/auth/me

Returns the authenticated user.

**Response 200:**
```json
{
  "id": 1,
  "name": "João Silva",
  "email": "joao@example.com"
}
```

---

### Farms

All farm endpoints require authentication.

#### GET /api/farms

List all farms belonging to the authenticated user.

**Response 200:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Quinta da Serra",
      "location": "Viseu, Portugal",
      "total_area_ha": 12.5,
      "fields_count": 4,
      "created_at": "2026-06-28T00:00:00.000000Z"
    }
  ],
  "meta": {
    "total": 1,
    "per_page": 15,
    "current_page": 1
  }
}
```

#### POST /api/farms

Create a new farm.

**Request body:**
```json
{
  "name": "Quinta da Serra",
  "location": "Viseu, Portugal",
  "total_area_ha": 12.5
}
```

**Response 201:**
```json
{
  "data": {
    "id": 1,
    "name": "Quinta da Serra",
    "location": "Viseu, Portugal",
    "total_area_ha": 12.5
  }
}
```

#### GET /api/farms/{id}

**Response 200:**
```json
{
  "data": {
    "id": 1,
    "name": "Quinta da Serra",
    "location": "Viseu, Portugal",
    "total_area_ha": 12.5,
    "fields": []
  }
}
```

#### PUT /api/farms/{id}

Update a farm. Same body as POST.

#### DELETE /api/farms/{id}

**Response 204** — No content.

---

### Fields

Fields belong to a Farm.

#### GET /api/farms/{farmId}/fields

#### POST /api/farms/{farmId}/fields

**Request body:**
```json
{
  "name": "Campo Norte",
  "area_ha": 3.2,
  "soil_type": "clay",
  "irrigated": true
}
```

#### GET /api/farms/{farmId}/fields/{id}

#### PUT /api/farms/{farmId}/fields/{id}

#### DELETE /api/farms/{farmId}/fields/{id}

---

### Crops

Available crop types (reference data), optionally enriched with FAOSTAT profile data.

#### GET /api/crops

Returns all crop types in the catalogue.

**Response 200:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Tomato",
      "scientific_name": "Solanum lycopersicum",
      "category": "vegetable",
      "avg_growth_days": 80,
      "icon": "fa-solid fa-apple-whole"
    }
  ]
}
```

#### GET /api/crops/{id}

Returns a single crop with full encyclopedia data (profile, soil suitabilities, yield benchmarks). Fields are `null` if not yet seeded or imported.

**Response 200:**
```json
{
  "data": {
    "id": 1,
    "name": "Tomato",
    "profile": {
      "faostat_item_code": "388",
      "description": "Tomato is the world's most widely grown vegetable crop…",
      "optimal_temp_min": 18,
      "optimal_temp_max": 27,
      "ph_min": 6.0,
      "ph_max": 6.8,
      "drought_tolerance": "low",
      "frost_tolerance": "low",
      "faostat_imported_at": null
    },
    "soil_suitabilities": [
      { "soil_type": "loam", "suitability": "ideal" },
      { "soil_type": "clay", "suitability": "marginal" }
    ],
    "yield_benchmarks": [
      { "year": 2022, "yield_kg_ha": 37500.00 }
    ]
  }
}
```

#### GET /api/crops/{id}/tips

Returns agronomist tips for a crop. Accepts optional `?soil_type=clay` query param — when provided, returns tips scoped to that soil type plus generic tips (soil_type IS NULL).

**Response 200:**
```json
{
  "data": [
    { "type": "soil", "soil_type": "clay", "body": "Raise beds and add compost on clay soils…" },
    { "type": "irrigation", "soil_type": null, "body": "Use drip irrigation for consistent moisture." }
  ]
}
```

---

### Encyclopedia (FAOSTAT)

Search and import crops from the FAOSTAT database into the local catalogue. The FAOSTAT item list (domain QCL) is cached in Redis for 24 hours.

#### GET /api/encyclopedia/search?q={query}

Requires auth. Query must be at least 2 characters.

**Response 200:**
```json
{
  "data": [
    { "faostat_code": "388", "name": "Tomatoes", "already_imported": true },
    { "faostat_code": "544", "name": "Tomatoes, cherry", "already_imported": false }
  ]
}
```

#### POST /api/encyclopedia/import

Import a FAOSTAT item into the local crop catalogue. Fetches global yield benchmarks (2013–2023, world aggregate) from FAOSTAT QCL at import time.

**Request body:**
```json
{
  "faostat_item_code": "388",
  "name": "Tomatoes"
}
```

**Response 201** — created crop with profile and benchmarks.

**Response 409** — crop already imported (returns existing data).

---

### Field Recommendations

#### GET /api/farms/{farmId}/fields/{fieldId}/recommendations

Returns crops recommended for a field based on its `soil_type`. Only crops with `ideal` or `suitable` suitability for that soil type are returned, ordered by suitability (ideal first).

**Response 200:**
```json
{
  "data": [
    { "id": 1, "name": "Wheat", "icon": "fa-solid fa-wheat-awn", "category": "cereal", "avg_growth_days": 240, "suitability": "ideal" },
    { "id": 3, "name": "Tomato", "icon": "fa-solid fa-apple-whole", "category": "vegetable", "avg_growth_days": 80, "suitability": "suitable" }
  ],
  "meta": { "soil_type": "loam" }
}
```

---

### Plantations

A Plantation links a Crop to a Field for a specific season.

#### GET /api/fields/{fieldId}/plantations

#### POST /api/fields/{fieldId}/plantations

**Request body:**
```json
{
  "crop_id": 1,
  "planted_at": "2026-03-15",
  "expected_harvest_at": "2026-06-15",
  "area_ha": 2.0,
  "notes": "First planting of the season."
}
```

#### GET /api/fields/{fieldId}/plantations/{id}

#### PATCH /api/fields/{fieldId}/plantations/{id}

Partial update (e.g., record actual harvest date).

**Request body:**
```json
{
  "harvested_at": "2026-06-20",
  "yield_kg": 4200
}
```

#### DELETE /api/fields/{fieldId}/plantations/{id}

---

## Error Responses

All errors follow a consistent format:

**422 Validation error:**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "name": ["The name field is required."],
    "area_ha": ["The area must be a positive number."]
  }
}
```

**401 Unauthenticated:**
```json
{
  "message": "Unauthenticated."
}
```

**403 Forbidden:**
```json
{
  "message": "This action is unauthorized."
}
```

**404 Not found:**
```json
{
  "message": "No query results for model [Farm] 999."
}
```

**500 Server error:**
```json
{
  "message": "Server Error."
}
```
