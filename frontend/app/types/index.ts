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

export interface CropProfile {
  id: number
  crop_id: number
  faostat_item_code: string | null
  description: string | null
  optimal_temp_min: number | null
  optimal_temp_max: number | null
  ph_min: number | null
  ph_max: number | null
  annual_rainfall_min: number | null
  annual_rainfall_max: number | null
  drought_tolerance: 'low' | 'medium' | 'high' | null
  frost_tolerance: 'low' | 'medium' | 'high' | null
  faostat_imported_at: string | null
}

export interface CropSoilSuitability {
  id: number
  crop_id: number
  soil_type: 'clay' | 'loam' | 'sandy' | 'silt'
  suitability: 'ideal' | 'suitable' | 'marginal' | 'unsuitable'
}

export interface CropYieldBenchmark {
  id: number
  crop_id: number
  country_code: string
  year: number
  yield_kg_ha: number
}

export interface CropTip {
  id: number
  crop_id: number
  type: 'planting' | 'harvesting' | 'soil' | 'irrigation' | 'pest' | 'general'
  soil_type: 'clay' | 'loam' | 'sandy' | 'silt' | null
  body: string
}

export interface FaostatItem {
  faostat_code: string
  name: string
  already_imported: boolean
}

export interface CropRecommendation {
  id: number
  name: string
  icon: string
  category: string
  avg_growth_days: number | null
  suitability: 'ideal' | 'suitable'
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
  profile?: CropProfile | null
  soil_suitabilities?: CropSoilSuitability[]
  yield_benchmarks?: CropYieldBenchmark[]
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
