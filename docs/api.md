# WiseFarm API Documentation

Base URL: `http://localhost:8000/api`

All endpoints return JSON. Protected endpoints require a valid Sanctum session cookie (call `/sanctum/csrf-cookie` first).

---

## Authentication

WiseFarm uses Laravel Sanctum with cookie-based SPA authentication.

### Flow

1. `GET /sanctum/csrf-cookie` — sets the XSRF-TOKEN cookie
2. `POST /api/auth/login` — authenticates and creates a session
3. All subsequent requests include the session cookie automatically

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
  "email": "joao@example.com",
  "created_at": "2026-06-28T00:00:00.000000Z"
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

Available crop types (reference data).

#### GET /api/crops

**Response 200:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Tomato",
      "scientific_name": "Solanum lycopersicum",
      "category": "vegetable",
      "avg_growth_days": 80
    }
  ]
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
