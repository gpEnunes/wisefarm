<template>
  <div :data-theme="dark ? 'dark' : 'light'" style="display:flex; flex-direction:column; gap:24px; min-width:0;">

    <!-- Header -->
    <div>
      <h1 style="font-size:22px; font-weight:800; margin:0 0 4px; letter-spacing:-0.4px;">Crop Encyclopedia</h1>
      <p style="font-size:14px; color:var(--muted,#73817a); margin:0;">
        Search the FAOSTAT database and import crops into your catalogue with full agronomic profiles.
      </p>
    </div>

    <!-- Search input -->
    <div style="position:relative; max-width:560px;">
      <i class="fa-solid fa-magnifying-glass" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--muted,#73817a); font-size:14px; pointer-events:none;"></i>
      <input
        v-model="query"
        type="text"
        placeholder="Search crops — e.g. Tomatoes, Wheat, Rice, Grapes…"
        style="width:100%; box-sizing:border-box; padding:11px 14px 11px 40px; border:1px solid var(--border,#e8e4d9); border-radius:12px; font-size:14px; background:var(--card,#fff); color:inherit; outline:none;"
        @input="onInput"
      />
      <div v-if="loading" style="position:absolute; right:14px; top:50%; transform:translateY(-50%);">
        <i class="fa-solid fa-circle-notch fa-spin" style="color:var(--muted,#73817a); font-size:13px;"></i>
      </div>
    </div>

    <!-- Error -->
    <div v-if="error" style="color:#e05252; font-size:14px;">{{ error }}</div>

    <!-- Results -->
    <div v-if="results.length > 0" style="display:flex; flex-direction:column; gap:14px;">
      <div
        v-for="item in results"
        :key="item.faostat_code"
        style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:16px; overflow:hidden;"
      >
        <!-- Card header -->
        <div style="display:flex; align-items:center; gap:14px; padding:18px 20px 14px;">
          <div style="width:46px; height:46px; border-radius:13px; background:rgba(45,106,79,0.10); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <i :class="item.icon" style="color:#2D6A4F; font-size:20px;"></i>
          </div>
          <div style="flex:1; min-width:0;">
            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
              <span style="font-size:16px; font-weight:700;">{{ item.name }}</span>
              <span style="font-size:11px; font-weight:600; padding:3px 9px; border-radius:99px; background:rgba(132,169,140,0.15); color:#52635a; text-transform:capitalize;">{{ item.category }}</span>
              <span style="font-size:11px; color:var(--muted,#73817a);">FAOSTAT {{ item.faostat_code }}</span>
            </div>
            <div v-if="item.description" style="font-size:13px; color:var(--muted,#73817a); margin-top:4px; line-height:1.5;">{{ item.description }}</div>
          </div>
          <div style="flex-shrink:0;">
            <span
              v-if="item.already_imported"
              style="font-size:12px; font-weight:600; padding:6px 14px; border-radius:99px; background:rgba(82,183,136,0.15); color:#2D6A4F; white-space:nowrap; display:flex; align-items:center; gap:6px;"
            >
              <i class="fa-solid fa-check" style="font-size:10px;"></i> Imported
            </span>
            <button
              v-else
              style="font-size:13px; font-weight:600; padding:7px 18px; border-radius:99px; background:#2D6A4F; color:#fff; border:none; cursor:pointer; white-space:nowrap; display:flex; align-items:center; gap:7px;"
              @click="openImport(item)"
            >
              <i class="fa-solid fa-file-import" style="font-size:12px;"></i> Import
            </button>
          </div>
        </div>

        <!-- Agronomic data grid (only when enriched data is present) -->
        <div
          v-if="item.optimal_temp_min != null || item.ph_min != null || item.annual_rainfall_min != null || item.drought_tolerance || item.soil_types.length"
          style="border-top:1px solid var(--border,#e8e4d9); padding:14px 20px 16px; display:flex; flex-wrap:wrap; gap:10px; align-items:flex-start;"
        >
          <!-- Temp -->
          <div v-if="item.optimal_temp_min != null" style="background:var(--bg,#F4F1EA); border-radius:10px; padding:10px 14px; min-width:110px;">
            <div style="font-size:10px; color:var(--muted,#73817a); font-weight:600; text-transform:uppercase; letter-spacing:.4px; margin-bottom:4px;">
              <i class="fa-solid fa-temperature-half" style="margin-right:4px; color:#E76F51;"></i>Temperature
            </div>
            <div style="font-size:14px; font-weight:700;">{{ item.optimal_temp_min }}–{{ item.optimal_temp_max }} °C</div>
          </div>

          <!-- pH -->
          <div v-if="item.ph_min != null" style="background:var(--bg,#F4F1EA); border-radius:10px; padding:10px 14px; min-width:100px;">
            <div style="font-size:10px; color:var(--muted,#73817a); font-weight:600; text-transform:uppercase; letter-spacing:.4px; margin-bottom:4px;">
              <i class="fa-solid fa-flask" style="margin-right:4px; color:#6C91C2;"></i>Soil pH
            </div>
            <div style="font-size:14px; font-weight:700;">{{ item.ph_min }}–{{ item.ph_max }}</div>
          </div>

          <!-- Rainfall -->
          <div v-if="item.annual_rainfall_min != null" style="background:var(--bg,#F4F1EA); border-radius:10px; padding:10px 14px; min-width:120px;">
            <div style="font-size:10px; color:var(--muted,#73817a); font-weight:600; text-transform:uppercase; letter-spacing:.4px; margin-bottom:4px;">
              <i class="fa-solid fa-cloud-rain" style="margin-right:4px; color:#4B9CD3;"></i>Rainfall
            </div>
            <div style="font-size:14px; font-weight:700;">{{ item.annual_rainfall_min }}–{{ item.annual_rainfall_max }} mm</div>
          </div>

          <!-- Growth days -->
          <div v-if="item.avg_growth_days" style="background:var(--bg,#F4F1EA); border-radius:10px; padding:10px 14px; min-width:100px;">
            <div style="font-size:10px; color:var(--muted,#73817a); font-weight:600; text-transform:uppercase; letter-spacing:.4px; margin-bottom:4px;">
              <i class="fa-solid fa-clock" style="margin-right:4px; color:#52B788;"></i>Growth cycle
            </div>
            <div style="font-size:14px; font-weight:700;">{{ item.avg_growth_days }} <span style="font-size:12px; font-weight:400; color:var(--muted,#73817a);">days</span></div>
          </div>

          <!-- Drought tolerance -->
          <div v-if="item.drought_tolerance" style="background:var(--bg,#F4F1EA); border-radius:10px; padding:10px 14px; min-width:100px;">
            <div style="font-size:10px; color:var(--muted,#73817a); font-weight:600; text-transform:uppercase; letter-spacing:.4px; margin-bottom:4px;">
              <i class="fa-solid fa-droplet-slash" style="margin-right:4px; color:#F4A261;"></i>Drought
            </div>
            <div :style="`font-size:14px; font-weight:700; text-transform:capitalize; color:${toleranceColor(item.drought_tolerance)};`">{{ item.drought_tolerance }}</div>
          </div>

          <!-- Frost tolerance -->
          <div v-if="item.frost_tolerance" style="background:var(--bg,#F4F1EA); border-radius:10px; padding:10px 14px; min-width:100px;">
            <div style="font-size:10px; color:var(--muted,#73817a); font-weight:600; text-transform:uppercase; letter-spacing:.4px; margin-bottom:4px;">
              <i class="fa-solid fa-snowflake" style="margin-right:4px; color:#4B9CD3;"></i>Frost
            </div>
            <div :style="`font-size:14px; font-weight:700; text-transform:capitalize; color:${toleranceColor(item.frost_tolerance)};`">{{ item.frost_tolerance }}</div>
          </div>

          <!-- Soil types -->
          <div v-if="item.soil_types.length" style="display:flex; flex-wrap:wrap; gap:6px; align-items:center; padding:10px 0;">
            <span
              v-for="s in item.soil_types"
              :key="s.soil"
              style="font-size:12px; font-weight:600; padding:4px 10px; border-radius:99px; text-transform:capitalize; display:flex; align-items:center; gap:5px;"
              :style="`background:${suitabilityBg(s.suitability)}; color:${suitabilityColor(s.suitability)};`"
            >
              {{ s.soil }} — {{ s.suitability }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty after search -->
    <div
      v-else-if="searched && !loading"
      style="text-align:center; padding:48px 0; color:var(--muted,#73817a); font-size:14px;"
    >
      No crops found for "{{ query }}" in the FAOSTAT database.
    </div>

    <!-- Default state -->
    <div
      v-else-if="!searched && !loading"
      style="text-align:center; padding:60px 0; color:var(--muted,#73817a); font-size:14px;"
    >
      <i class="fa-solid fa-book-open" style="font-size:32px; margin-bottom:16px; display:block; opacity:.3;"></i>
      Type at least 2 characters to search 138 FAOSTAT crop types.
    </div>

    <!-- Import confirmation modal -->
    <AppModal
      :model-value="!!importTarget"
      :title="importTarget ? `Import ${importTarget.name}` : ''"
      @update:model-value="importTarget = null"
    >
      <div v-if="importTarget">
        <p style="font-size:14px; color:var(--muted,#73817a); margin:0 0 16px; line-height:1.6;">
          Import <strong>{{ importTarget.name }}</strong> (FAOSTAT {{ importTarget.faostat_code }}) into your crop catalogue.
        </p>

        <div v-if="importTarget.description" style="font-size:13px; color:var(--muted,#73817a); margin-bottom:16px; line-height:1.55; background:var(--bg,#F4F1EA); border-radius:10px; padding:12px 14px;">
          {{ importTarget.description }}
        </div>

        <div
          v-if="importTarget.optimal_temp_min != null || importTarget.ph_min != null"
          style="display:grid; grid-template-columns:repeat(auto-fill,minmax(120px,1fr)); gap:8px; margin-bottom:16px;"
        >
          <div v-if="importTarget.optimal_temp_min != null" style="background:var(--bg,#F4F1EA); border-radius:10px; padding:10px 12px; text-align:center;">
            <div style="font-size:10px; color:var(--muted,#73817a); font-weight:600; text-transform:uppercase; margin-bottom:4px;">Temp</div>
            <div style="font-size:13px; font-weight:700;">{{ importTarget.optimal_temp_min }}–{{ importTarget.optimal_temp_max }}°C</div>
          </div>
          <div v-if="importTarget.ph_min != null" style="background:var(--bg,#F4F1EA); border-radius:10px; padding:10px 12px; text-align:center;">
            <div style="font-size:10px; color:var(--muted,#73817a); font-weight:600; text-transform:uppercase; margin-bottom:4px;">pH</div>
            <div style="font-size:13px; font-weight:700;">{{ importTarget.ph_min }}–{{ importTarget.ph_max }}</div>
          </div>
          <div v-if="importTarget.avg_growth_days" style="background:var(--bg,#F4F1EA); border-radius:10px; padding:10px 12px; text-align:center;">
            <div style="font-size:10px; color:var(--muted,#73817a); font-weight:600; text-transform:uppercase; margin-bottom:4px;">Cycle</div>
            <div style="font-size:13px; font-weight:700;">{{ importTarget.avg_growth_days }}d</div>
          </div>
          <div v-if="importTarget.drought_tolerance" style="background:var(--bg,#F4F1EA); border-radius:10px; padding:10px 12px; text-align:center;">
            <div style="font-size:10px; color:var(--muted,#73817a); font-weight:600; text-transform:uppercase; margin-bottom:4px;">Drought</div>
            <div :style="`font-size:13px; font-weight:700; text-transform:capitalize; color:${toleranceColor(importTarget.drought_tolerance!)};`">{{ importTarget.drought_tolerance }}</div>
          </div>
        </div>

        <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:8px;">
          <button
            style="font-size:13px; font-weight:600; padding:8px 18px; border-radius:99px; border:1px solid var(--border,#e8e4d9); background:transparent; cursor:pointer; color:inherit;"
            @click="importTarget = null"
          >
            Cancel
          </button>
          <button
            :disabled="importing"
            style="font-size:13px; font-weight:600; padding:8px 20px; border-radius:99px; background:#2D6A4F; color:#fff; border:none; cursor:pointer; display:flex; align-items:center; gap:7px;"
            @click="confirmImport"
          >
            <i class="fa-solid fa-file-import" style="font-size:11px;"></i>
            {{ importing ? 'Importing…' : 'Confirm import' }}
          </button>
        </div>
      </div>
    </AppModal>

  </div>
</template>

<script setup lang="ts">
import type { FaostatItem } from '~/types'

definePageMeta({ layout: 'dashboard', middleware: ['auth'], ssr: false })
useHead({ title: 'WiseFarm — Encyclopedia' })

const dark = useState('wf-dark', () => false)
const api = useApi()
const toast = useWfToast()

const query = ref('')
const results = ref<FaostatItem[]>([])
const loading = ref(false)
const searched = ref(false)
const error = ref('')
const importTarget = ref<FaostatItem | null>(null)
const importing = ref(false)

let debounceTimer: ReturnType<typeof setTimeout> | null = null

const onInput = () => {
  if (debounceTimer) clearTimeout(debounceTimer)
  debounceTimer = setTimeout(search, 400)
}

const search = async () => {
  const q = query.value.trim()
  if (q.length < 2) {
    results.value = []
    searched.value = false
    return
  }
  loading.value = true
  error.value = ''
  try {
    const res = await api.get<{ data: FaostatItem[] }>(`/encyclopedia/search?q=${encodeURIComponent(q)}`)
    results.value = res.data
    searched.value = true
  } catch {
    error.value = 'Failed to reach the search service. Please try again.'
  } finally {
    loading.value = false
  }
}

const openImport = (item: FaostatItem) => { importTarget.value = item }

const confirmImport = async () => {
  if (!importTarget.value) return
  importing.value = true
  try {
    await api.post('/encyclopedia/import', {
      faostat_item_code: importTarget.value.faostat_code,
      name: importTarget.value.name,
    })
    toast.show(`${importTarget.value.name} imported successfully.`, 'success')
    const code = importTarget.value.faostat_code
    results.value = results.value.map(r => r.faostat_code === code ? { ...r, already_imported: true } : r)
    importTarget.value = null
  } catch {
    toast.show('Import failed. Please try again.', 'error')
  } finally {
    importing.value = false
  }
}

const toleranceColor = (v: string) =>
  v === 'high' ? '#2D6A4F' : v === 'medium' ? '#F4A261' : '#e05252'

const suitabilityColor = (v: string) => {
  const map: Record<string, string> = { ideal: '#2D6A4F', suitable: '#52B788', marginal: '#F4A261', unsuitable: '#e05252' }
  return map[v] ?? '#73817a'
}

const suitabilityBg = (v: string) => {
  const map: Record<string, string> = {
    ideal: 'rgba(45,106,79,0.12)', suitable: 'rgba(82,183,136,0.15)',
    marginal: 'rgba(244,162,97,0.15)', unsuitable: 'rgba(224,82,82,0.12)',
  }
  return map[v] ?? 'rgba(115,129,122,0.1)'
}
</script>
