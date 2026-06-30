/**
 * Shared domain types for WiseFarm frontend.
 * Import these instead of using `any` in composables, stores, and pages.
 */

/**
 * A farm owned by the authenticated user.
 */
export interface Farm {
  id: number
  name: string
  location: string | null
  total_area_ha: number | null
  fields_count?: number
  created_at: string
}

/**
 * A field belonging to a farm.
 */
export interface Field {
  id: number
  farm_id: number
  name: string
  area_ha: number | null
  soil_type: string | null
  irrigated: boolean
  created_at: string
}

/**
 * Authenticated user shape returned by /auth/me, /auth/login, and /auth/register.
 */
export interface AuthUser {
  id: number
  name: string
  email: string
}

/**
 * A crop type from the reference catalogue.
 */
export interface Crop {
  id: number
  name: string
  scientific_name: string | null
  category: string
  avg_growth_days: number | null
  icon: string
}

/**
 * A plantation linking a crop to a field for a planting cycle.
 */
export interface Plantation {
  id: number
  field_id: number
  crop_id: number
  crop?: Crop
  planted_at: string
  expected_harvest_at: string | null
  harvested_at: string | null
  area_ha: number | null
  yield_kg: number | null
  notes: string | null
  created_at: string
}
