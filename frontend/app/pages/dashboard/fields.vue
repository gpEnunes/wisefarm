<template>
  <div :data-theme="dark ? 'dark' : 'light'" style="display:flex; flex-direction:column; gap:24px; min-width:0;">

    <!-- ── Farm list (no farm selected) ── -->
    <template v-if="!selectedFarm">
      <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
        <div>
          <h1 style="font-size:22px; font-weight:800; margin:0 0 4px; letter-spacing:-0.4px;">Farms &amp; Fields</h1>
          <p style="font-size:14px; color:var(--muted,#73817a); margin:0;">
            {{ apiFarms.length }} farm{{ apiFarms.length !== 1 ? 's' : '' }} in your account.
          </p>
        </div>
        <button @click="openNewFarm" style="display:flex; align-items:center; gap:8px; padding:10px 18px; border:none; border-radius:10px; background:#2D6A4F; color:#fff; font-family:inherit; font-size:14px; font-weight:600; cursor:pointer;">
          <i class="fa-solid fa-plus"></i> New Farm
        </button>
      </div>

      <div v-if="farmsLoading" style="font-size:14px; color:var(--muted,#73817a); padding:20px 0;">Loading farms…</div>

      <div v-else-if="apiFarms.length === 0" style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:16px; padding:40px; text-align:center; color:var(--muted,#73817a);">
        <i class="fa-solid fa-tractor" style="font-size:32px; margin-bottom:12px; color:#84A98C;"></i>
        <p style="margin:0; font-size:15px; font-weight:500;">No farms yet. Create your first farm to get started.</p>
      </div>

      <div v-else style="display:grid; grid-template-columns:repeat(auto-fill,minmax(320px,1fr)); gap:16px;">
        <div
          v-for="farm in apiFarms"
          :key="farm.id"
          @click="selectFarm(farm)"
          style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:16px; padding:20px; cursor:pointer; transition:box-shadow .2s;"
          onmouseover="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.07)'"
          onmouseout="this.style.boxShadow='none'"
        >
          <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:14px;">
            <div>
              <div style="font-size:15px; font-weight:700; margin-bottom:3px;">{{ farm.name }}</div>
              <div style="font-size:13px; color:var(--muted,#73817a);">{{ farm.location ?? '—' }}</div>
            </div>
            <div style="display:flex; gap:6px;" @click.stop>
              <button @click="openEditFarm(farm)" style="padding:5px 10px; border:1px solid var(--border,#e8e4d9); border-radius:7px; background:transparent; color:var(--text,#1f2a24); font-family:inherit; font-size:12px; cursor:pointer;">Edit</button>
              <button @click="deleteFarm(farm.id)" style="padding:5px 10px; border:1px solid rgba(231,111,81,0.3); border-radius:7px; background:transparent; color:#E76F51; font-family:inherit; font-size:12px; cursor:pointer;">Delete</button>
            </div>
          </div>
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
            <div style="background:var(--bg,#F4F1EA); border-radius:10px; padding:12px;">
              <div style="font-size:11px; color:var(--muted,#73817a); font-weight:600; text-transform:uppercase; letter-spacing:.4px; margin-bottom:4px;">Total area</div>
              <div style="font-size:16px; font-weight:700;">{{ farm.total_area_ha ?? '—' }} <span style="font-size:12px; font-weight:500; color:var(--muted,#73817a);">ha</span></div>
            </div>
            <div style="background:var(--bg,#F4F1EA); border-radius:10px; padding:12px;">
              <div style="font-size:11px; color:var(--muted,#73817a); font-weight:600; text-transform:uppercase; letter-spacing:.4px; margin-bottom:4px;">Fields</div>
              <div style="font-size:16px; font-weight:700;">{{ farm.fields_count ?? 0 }}</div>
            </div>
          </div>
          <div style="margin-top:12px; font-size:12px; color:#84A98C; font-weight:600;">Click to view fields →</div>
        </div>
      </div>
    </template>

    <!-- ── Field list for selected farm ── -->
    <template v-else>
      <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
        <div>
          <button @click="selectedFarm = null; apiFields = []" style="display:inline-flex; align-items:center; gap:6px; padding:6px 12px; border:1px solid var(--border,#e8e4d9); border-radius:8px; background:transparent; color:var(--muted,#73817a); font-family:inherit; font-size:13px; cursor:pointer; margin-bottom:8px;">
            <i class="fa-solid fa-arrow-left" style="font-size:11px;"></i> Back to farms
          </button>
          <h1 style="font-size:22px; font-weight:800; margin:0 0 4px; letter-spacing:-0.4px;">{{ selectedFarm.name }}</h1>
          <p style="font-size:14px; color:var(--muted,#73817a); margin:0;">{{ selectedFarm.location ?? '' }}</p>
        </div>
        <button @click="openNewField" style="display:flex; align-items:center; gap:8px; padding:10px 18px; border:none; border-radius:10px; background:#2D6A4F; color:#fff; font-family:inherit; font-size:14px; font-weight:600; cursor:pointer;">
          <i class="fa-solid fa-plus"></i> New Field
        </button>
      </div>

      <div v-if="fieldsLoading" style="font-size:14px; color:var(--muted,#73817a); padding:20px 0;">Loading fields…</div>

      <div v-else-if="apiFields.length === 0" style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:16px; padding:40px; text-align:center; color:var(--muted,#73817a);">
        <p style="margin:0; font-size:15px; font-weight:500;">No fields yet. Add the first field for this farm.</p>
      </div>

      <div v-else style="display:grid; grid-template-columns:repeat(auto-fill,minmax(300px,1fr)); gap:16px;">
        <div
          v-for="field in apiFields"
          :key="field.id"
          style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:16px; padding:20px;"
        >
          <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:14px;">
            <div>
              <div style="font-size:15px; font-weight:700; margin-bottom:3px;">{{ field.name }}</div>
              <div style="font-size:13px; color:var(--muted,#73817a);">
                {{ field.area_ha ? field.area_ha + ' ha' : '—' }} · {{ field.soil_type ?? '—' }} · {{ field.irrigated ? 'Irrigated' : 'Dryland' }}
              </div>
            </div>
            <div style="display:flex; gap:6px;">
              <button @click="openEditField(field)" style="padding:5px 10px; border:1px solid var(--border,#e8e4d9); border-radius:7px; background:transparent; color:var(--text,#1f2a24); font-family:inherit; font-size:12px; cursor:pointer;">Edit</button>
              <button @click="deleteField(field.id)" style="padding:5px 10px; border:1px solid rgba(231,111,81,0.3); border-radius:7px; background:transparent; color:#E76F51; font-family:inherit; font-size:12px; cursor:pointer;">Delete</button>
            </div>
          </div>
          <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:14px;">
            <span v-if="field.irrigated" style="font-size:11.5px; font-weight:600; padding:3px 9px; border-radius:99px; background:rgba(82,183,136,0.14); color:#2D6A4F;">Irrigated</span>
            <span v-if="field.soil_type" style="font-size:11.5px; font-weight:600; padding:3px 9px; border-radius:99px; background:rgba(132,169,140,0.14); color:#52635a;">{{ field.soil_type }}</span>
          </div>

          <!-- Recommendations toggle -->
          <button
            v-if="field.soil_type"
            @click="toggleRecommendations(field)"
            style="width:100%; padding:8px; border:1px solid var(--border,#e8e4d9); border-radius:8px; background:transparent; font-family:inherit; font-size:13px; font-weight:600; color:#2D6A4F; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:6px;"
          >
            <i :class="expandedRec === field.id ? 'fa-solid fa-chevron-up' : 'fa-solid fa-seedling'" style="font-size:12px;"></i>
            {{ expandedRec === field.id ? 'Hide recommendations' : 'View crop recommendations' }}
          </button>

          <!-- Recommendations panel -->
          <div v-if="expandedRec === field.id" style="margin-top:12px; border-top:1px solid var(--border,#e8e4d9); padding-top:12px;">
            <div v-if="recLoading && !recommendationMap.has(field.id)" style="font-size:13px; color:var(--muted,#73817a);">Loading…</div>
            <div v-else-if="recommendationMap.get(field.id)?.length === 0" style="font-size:13px; color:var(--muted,#73817a);">No recommendations yet. Import crops via the Encyclopedia.</div>
            <div v-else style="display:flex; flex-direction:column; gap:8px;">
              <div
                v-for="rec in recommendationMap.get(field.id)"
                :key="rec.id"
                style="display:flex; align-items:center; gap:10px; padding:8px 10px; background:var(--bg,#F4F1EA); border-radius:8px;"
              >
                <i :class="rec.icon" style="color:#2D6A4F; font-size:14px; width:18px; text-align:center;"></i>
                <NuxtLink :to="`/dashboard/crops/${rec.id}`" style="flex:1; font-size:13px; font-weight:600; color:inherit; text-decoration:none;">{{ rec.name }}</NuxtLink>
                <span :style="`font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px; background:${suitabilityBg(rec.suitability)}; color:${suitabilityColor(rec.suitability)};`">
                  {{ rec.suitability }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>

    <!-- Farm modal -->
    <AppModal v-model="showFarmModal" :title="editingFarm ? 'Edit Farm' : 'New Farm'">
      <div style="display:flex; flex-direction:column; gap:16px;">
        <AppInput v-model="farmForm.name" label="Farm name *" type="text" placeholder="My Farm" />
        <AppInput v-model="farmForm.location" label="Location" type="text" placeholder="Alentejo, Portugal" />
        <AppInput v-model="farmForm.total_area_ha" label="Total area (ha)" type="number" step="0.01" min="0" placeholder="120.5" />
        <button @click="saveFarm" style="width:100%; padding:12px; border:none; border-radius:10px; background:#2D6A4F; color:#fff; font-family:inherit; font-size:14px; font-weight:600; cursor:pointer; margin-top:4px;">
          {{ editingFarm ? 'Update Farm' : 'Create Farm' }}
        </button>
      </div>
    </AppModal>

    <!-- Field modal -->
    <AppModal v-model="showFieldModal" :title="editingField ? 'Edit Field' : 'New Field'">
      <div style="display:flex; flex-direction:column; gap:16px;">
        <AppInput v-model="fieldForm.name" label="Field name *" type="text" placeholder="North Field" />
        <AppInput v-model="fieldForm.area_ha" label="Area (ha)" type="number" step="0.01" min="0" placeholder="5.5" />
        <div style="display:flex; flex-direction:column; gap:6px;">
          <label style="font-size:13px; font-weight:600; color:#52635a;">Soil type</label>
          <select v-model="fieldForm.soil_type" style="padding:11px 14px; border:1.5px solid #e0dccf; border-radius:10px; font-family:inherit; font-size:14px; background:#fff; color:#1f2a24; outline:none; width:100%;">
            <option value="loam">Loam</option>
            <option value="clay">Clay</option>
            <option value="sandy">Sandy</option>
            <option value="silt">Silt</option>
          </select>
        </div>
        <div style="display:flex; align-items:center; gap:10px;">
          <input type="checkbox" id="irrigated-check" v-model="fieldForm.irrigated" style="width:16px; height:16px; cursor:pointer;" />
          <label for="irrigated-check" style="font-size:14px; font-weight:500; cursor:pointer;">Irrigated</label>
        </div>
        <button @click="saveField" style="width:100%; padding:12px; border:none; border-radius:10px; background:#2D6A4F; color:#fff; font-family:inherit; font-size:14px; font-weight:600; cursor:pointer; margin-top:4px;">
          {{ editingField ? 'Update Field' : 'Create Field' }}
        </button>
      </div>
    </AppModal>

  </div>
</template>

<script setup lang="ts">
import type { Farm, Field, CropRecommendation } from '~/types'

definePageMeta({ layout: 'dashboard', middleware: ['auth'], ssr: false })
useHead({ title: 'WiseFarm — Farms & Fields' })

const dark = useState('wf-dark', () => false)
const api = useApi()
const toast = useWfToast()

// ─── API: Farms ───────────────────────────────────────────────────────────────
const apiFarms = ref<Farm[]>([])
const farmsLoading = ref(false)

/** Load the authenticated user's farms from the API. Skips if already in flight. */
const loadFarms = async () => {
  if (farmsLoading.value) return
  farmsLoading.value = true
  try {
    const res = await api.get<{ data: Farm[] }>('/farms')
    apiFarms.value = res.data
  } catch {
    // keep empty
  } finally {
    farmsLoading.value = false
  }
}

// ─── Farms CRUD ───────────────────────────────────────────────────────────────
const showFarmModal = ref(false)
const editingFarm = ref<Farm | null>(null)
const farmForm = reactive({ name: '', location: '', total_area_ha: '' })

/** Open the farm modal for creating a new farm. */
const openNewFarm = () => {
  editingFarm.value = null
  Object.assign(farmForm, { name: '', location: '', total_area_ha: '' })
  showFarmModal.value = true
}

/**
 * Open the farm modal pre-populated for editing an existing farm.
 *
 * @param farm - Farm to edit
 */
const openEditFarm = (farm: Farm) => {
  editingFarm.value = farm
  Object.assign(farmForm, {
    name: farm.name,
    location: farm.location ?? '',
    total_area_ha: farm.total_area_ha != null ? String(farm.total_area_ha) : '',
  })
  showFarmModal.value = true
}

/** POST or PUT the farm form, reload list, show toast. */
const saveFarm = async () => {
  try {
    const body = {
      name: farmForm.name,
      location: farmForm.location || null,
      total_area_ha: farmForm.total_area_ha !== '' ? Number(farmForm.total_area_ha) : null,
    }
    if (editingFarm.value) {
      const res = await api.put<{ data: Farm }>(`/farms/${editingFarm.value.id}`, body)
      apiFarms.value = apiFarms.value.map(f => f.id === editingFarm.value!.id ? res.data : f)
      toast.show('Farm updated.', 'success')
    } else {
      const res = await api.post<{ data: Farm }>('/farms', body)
      apiFarms.value = [...apiFarms.value, res.data]
      toast.show('Farm created.', 'success')
    }
    showFarmModal.value = false
  } catch {
    toast.show('Something went wrong.', 'error')
  }
}

/**
 * Delete a farm after confirmation.
 *
 * @param id - Farm primary key
 */
const deleteFarm = async (id: number) => {
  if (!confirm('Delete this farm? All fields and plantations will be removed.')) return
  try {
    await api.del(`/farms/${id}`)
    apiFarms.value = apiFarms.value.filter(f => f.id !== id)
    if (selectedFarm.value?.id === id) { selectedFarm.value = null; apiFields.value = [] }
    toast.show('Farm deleted.', 'success')
  } catch {
    toast.show('Something went wrong.', 'error')
  }
}

// ─── Fields CRUD ──────────────────────────────────────────────────────────────
const selectedFarm = ref<Farm | null>(null)
const apiFields = ref<Field[]>([])
const fieldsLoading = ref(false)
const showFieldModal = ref(false)
const editingField = ref<Field | null>(null)
const fieldForm = reactive({ name: '', area_ha: '', soil_type: 'loam', irrigated: false })

/**
 * Load all fields for the given farm.
 *
 * @param farm - Parent farm
 */
const loadFields = async (farm: Farm) => {
  fieldsLoading.value = true
  try {
    const res = await api.get<{ data: Field[] }>(`/farms/${farm.id}/fields`)
    apiFields.value = res.data
  } catch {
    toast.show('Failed to load fields.', 'error')
  } finally {
    fieldsLoading.value = false
  }
}

/**
 * Select a farm and load its fields.
 *
 * @param farm - Farm to drill into
 */
const selectFarm = (farm: Farm) => {
  selectedFarm.value = farm
  loadFields(farm)
}

/** Open the field modal for a new field. */
const openNewField = () => {
  editingField.value = null
  Object.assign(fieldForm, { name: '', area_ha: '', soil_type: 'loam', irrigated: false })
  showFieldModal.value = true
}

/**
 * Open the field modal pre-populated for editing.
 *
 * @param field - Field to edit
 */
const openEditField = (field: Field) => {
  editingField.value = field
  Object.assign(fieldForm, {
    name: field.name,
    area_ha: field.area_ha != null ? String(field.area_ha) : '',
    soil_type: field.soil_type ?? 'loam',
    irrigated: field.irrigated,
  })
  showFieldModal.value = true
}

/** POST or PUT the field form, optimistic update, show toast. */
const saveField = async () => {
  if (!selectedFarm.value) return
  try {
    const body = {
      name: fieldForm.name,
      area_ha: fieldForm.area_ha !== '' ? Number(fieldForm.area_ha) : null,
      soil_type: fieldForm.soil_type || null,
      irrigated: fieldForm.irrigated,
    }
    if (editingField.value) {
      const res = await api.put<{ data: Field }>(`/farms/${selectedFarm.value.id}/fields/${editingField.value.id}`, body)
      apiFields.value = apiFields.value.map(f => f.id === editingField.value!.id ? res.data : f)
      toast.show('Field updated.', 'success')
    } else {
      const res = await api.post<{ data: Field }>(`/farms/${selectedFarm.value.id}/fields`, body)
      apiFields.value = [...apiFields.value, res.data]
      toast.show('Field created.', 'success')
    }
    showFieldModal.value = false
  } catch {
    toast.show('Something went wrong.', 'error')
  }
}

/**
 * Delete a field after confirmation.
 *
 * @param id - Field primary key
 */
const deleteField = async (id: number) => {
  if (!selectedFarm.value) return
  if (!confirm('Delete this field? This cannot be undone.')) return
  try {
    await api.del(`/farms/${selectedFarm.value.id}/fields/${id}`)
    apiFields.value = apiFields.value.filter(f => f.id !== id)
    toast.show('Field deleted.', 'success')
  } catch {
    toast.show('Something went wrong.', 'error')
  }
}

// ─── Recommendations ──────────────────────────────────────────────────────────
const recommendationMap = ref<Map<number, CropRecommendation[]>>(new Map())
const expandedRec = ref<number | null>(null)
const recLoading = ref(false)

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

const toggleRecommendations = async (field: Field) => {
  if (expandedRec.value === field.id) { expandedRec.value = null; return }
  expandedRec.value = field.id
  if (recommendationMap.value.has(field.id)) return
  recLoading.value = true
  try {
    const res = await api.get<{ data: CropRecommendation[] }>(`/farms/${selectedFarm.value!.id}/fields/${field.id}/recommendations`)
    recommendationMap.value = new Map(recommendationMap.value).set(field.id, res.data)
  } catch {
    toast.show('Failed to load recommendations.', 'error')
  } finally {
    recLoading.value = false
  }
}

onMounted(() => loadFarms())
</script>
