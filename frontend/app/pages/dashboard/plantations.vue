<template>
  <div :data-theme="dark ? 'dark' : 'light'" style="display:flex; flex-direction:column; gap:24px; min-width:0;">

    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
      <div>
        <h1 style="font-size:22px; font-weight:800; margin:0 0 4px; letter-spacing:-0.4px;">Plantations</h1>
        <p style="font-size:14px; color:var(--muted,#73817a); margin:0;">Active planting cycles and their progress.</p>
      </div>
      <button v-if="selectedPlantationFieldId" @click="openNewPlantation" style="display:flex; align-items:center; gap:8px; padding:10px 18px; border:none; border-radius:10px; background:#2D6A4F; color:#fff; font-family:inherit; font-size:14px; font-weight:600; cursor:pointer;">
        <i class="fa-solid fa-plus"></i> New Plantation
      </button>
    </div>

    <!-- Field selector -->
    <div style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:14px; padding:16px 20px; display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
      <label style="font-size:13px; font-weight:600; color:var(--muted,#73817a); white-space:nowrap;">Select field:</label>
      <select
        v-model="selectedPlantationFieldId"
        style="flex:1; min-width:200px; padding:9px 14px; border:1.5px solid #e0dccf; border-radius:10px; font-family:inherit; font-size:14px; background:#fff; color:#1f2a24; outline:none;"
      >
        <option :value="null">— Choose a field —</option>
        <option v-for="f in allFieldsForPicker" :key="f.id" :value="f.id">{{ f.name }}</option>
      </select>
      <span v-if="allFieldsForPicker.length === 0 && !farmsLoading" style="font-size:13px; color:var(--muted,#73817a);">
        No fields found. Add fields in the Fields section first.
      </span>
    </div>

    <div v-if="plantationsLoading" style="font-size:14px; color:var(--muted,#73817a); padding:20px 0;">Loading plantations…</div>

    <!-- Real plantations table -->
    <div v-else-if="apiPlantations.length > 0" style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:18px; overflow:hidden;">
      <div style="padding:20px 22px; border-bottom:1px solid var(--border,#e8e4d9); display:grid; grid-template-columns:2fr 1fr 1fr 1fr 120px; gap:12px; font-size:12px; font-weight:600; color:var(--muted,#73817a); text-transform:uppercase; letter-spacing:.5px;">
        <span>Crop</span><span>Planted</span><span>Harvest</span><span>Status</span><span></span>
      </div>
      <div
        v-for="(p, i) in apiPlantations"
        :key="p.id"
        :style="`padding:18px 22px; display:grid; grid-template-columns:2fr 1fr 1fr 1fr 120px; gap:12px; align-items:center; ${i < apiPlantations.length - 1 ? 'border-bottom:1px solid var(--border,#e8e4d9);' : ''} transition:background .15s;`"
        onmouseover="this.style.background='var(--hover,#f1f5f1)'"
        onmouseout="this.style.background='transparent'"
      >
        <div>
          <div style="font-size:14px; font-weight:600;">{{ p.crop?.name ?? '—' }}</div>
          <div v-if="p.area_ha" style="font-size:12px; color:var(--muted,#73817a);">{{ p.area_ha }} ha</div>
          <div v-if="p.notes" style="font-size:12px; color:var(--muted,#73817a); margin-top:2px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:200px;">{{ p.notes }}</div>
        </div>
        <span style="font-size:13px; color:var(--muted,#73817a);">{{ formatDate(p.planted_at) }}</span>
        <span style="font-size:13px; color:var(--muted,#73817a);">{{ p.expected_harvest_at ? formatDate(p.expected_harvest_at) : '—' }}</span>
        <span :style="`font-size:12px; font-weight:600; padding:4px 10px; border-radius:99px; background:${plantationStatus(p).bg}; color:${plantationStatus(p).color};`">
          {{ plantationStatus(p).label }}
        </span>
        <div style="display:flex; gap:6px; justify-content:flex-end;">
          <button v-if="!p.harvested_at" @click="openRecordHarvest(p)" style="padding:6px 10px; border:1px solid var(--border,#e8e4d9); border-radius:8px; background:transparent; color:var(--text,#1f2a24); font-family:inherit; font-size:12px; cursor:pointer; white-space:nowrap;">Harvest</button>
          <button @click="deletePlantation(p)" style="padding:6px 10px; border:1px solid rgba(231,111,81,0.3); border-radius:8px; background:transparent; color:#E76F51; font-family:inherit; font-size:12px; cursor:pointer;">Del</button>
        </div>
      </div>
    </div>

    <!-- Empty state after field selection -->
    <div v-else-if="selectedPlantationFieldId && !plantationsLoading" style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:16px; padding:40px; text-align:center; color:var(--muted,#73817a);">
      <i class="fa-solid fa-tree" style="font-size:32px; margin-bottom:12px; color:#84A98C;"></i>
      <p style="margin:0; font-size:15px; font-weight:500;">No plantations for this field. Click "New Plantation" to add one.</p>
    </div>

    <!-- Static preview before a field is selected -->
    <div v-else-if="!selectedPlantationFieldId" style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:18px; overflow:hidden; opacity:0.5;">
      <div style="padding:20px 22px; border-bottom:1px solid var(--border,#e8e4d9); display:grid; grid-template-columns:2fr 1fr 1fr 1fr 1fr 100px; gap:12px; font-size:12px; font-weight:600; color:var(--muted,#73817a); text-transform:uppercase; letter-spacing:.5px;">
        <span>Plot / Crop</span><span>Planted</span><span>Harvest</span><span>Progress</span><span>Status</span><span></span>
      </div>
      <div v-for="(p, i) in staticPlantations" :key="p.plot" :style="`padding:18px 22px; display:grid; grid-template-columns:2fr 1fr 1fr 1fr 1fr 100px; gap:12px; align-items:center; ${i < staticPlantations.length - 1 ? 'border-bottom:1px solid var(--border,#e8e4d9);' : ''}`">
        <div>
          <div style="font-size:14px; font-weight:600;">{{ p.crop }}</div>
          <div style="font-size:12px; color:var(--muted,#73817a);">{{ p.plot }}</div>
        </div>
        <span style="font-size:13px; color:var(--muted,#73817a);">{{ p.planted }}</span>
        <span style="font-size:13px; color:var(--muted,#73817a);">{{ p.harvest }}</span>
        <div>
          <div style="display:flex; align-items:center; gap:8px;">
            <div style="flex:1; height:5px; background:var(--bg,#F4F1EA); border-radius:99px; overflow:hidden;">
              <div :style="`width:${p.pct}%; height:100%; background:#2D6A4F; border-radius:99px;`"></div>
            </div>
            <span style="font-size:12px; font-weight:600; color:#2D6A4F; min-width:32px;">{{ p.pct }}%</span>
          </div>
        </div>
        <span :style="`font-size:12px; font-weight:600; padding:4px 10px; border-radius:99px; background:${p.sb}; color:${p.sc};`">{{ p.status }}</span>
        <div></div>
      </div>
    </div>

    <!-- New Plantation modal -->
    <AppModal v-model="showPlantationModal" title="New Plantation">
      <div style="display:flex; flex-direction:column; gap:16px;">
        <div style="display:flex; flex-direction:column; gap:6px;">
          <label style="font-size:13px; font-weight:600; color:#52635a;">Field *</label>
          <select v-model="plantationForm.field_id" style="padding:11px 14px; border:1.5px solid #e0dccf; border-radius:10px; font-family:inherit; font-size:14px; background:#fff; color:#1f2a24; outline:none; width:100%;">
            <option value="">— Select field —</option>
            <option v-for="f in allFieldsForPicker" :key="f.id" :value="String(f.id)">{{ f.name }}</option>
          </select>
        </div>
        <div style="display:flex; flex-direction:column; gap:6px;">
          <label style="font-size:13px; font-weight:600; color:#52635a;">Crop *</label>
          <select v-model="plantationForm.crop_id" style="padding:11px 14px; border:1.5px solid #e0dccf; border-radius:10px; font-family:inherit; font-size:14px; background:#fff; color:#1f2a24; outline:none; width:100%;">
            <option value="">— Select crop —</option>
            <option v-for="c in apiCrops" :key="c.id" :value="String(c.id)">{{ c.name }}</option>
          </select>
        </div>
        <AppInput v-model="plantationForm.planted_at" label="Planted on *" type="date" />
        <AppInput v-model="plantationForm.expected_harvest_at" label="Expected harvest" type="date" />
        <AppInput v-model="plantationForm.area_ha" label="Area (ha)" type="number" step="0.01" min="0.01" placeholder="2.5" />
        <AppInput v-model="plantationForm.notes" label="Notes" type="text" placeholder="Optional notes…" />
        <button @click="savePlantation" style="width:100%; padding:12px; border:none; border-radius:10px; background:#2D6A4F; color:#fff; font-family:inherit; font-size:14px; font-weight:600; cursor:pointer; margin-top:4px;">
          Create Plantation
        </button>
      </div>
    </AppModal>

    <!-- Record Harvest modal -->
    <AppModal v-model="showHarvestModal" title="Record Harvest">
      <div style="display:flex; flex-direction:column; gap:16px;">
        <p v-if="editingPlantation" style="margin:0; font-size:14px; color:var(--muted,#73817a);">
          Recording harvest for <strong>{{ editingPlantation.crop?.name ?? 'plantation' }}</strong>.
        </p>
        <AppInput v-model="harvestForm.harvested_at" label="Harvest date *" type="date" />
        <AppInput v-model="harvestForm.yield_kg" label="Yield (kg)" type="number" step="0.01" min="0" placeholder="4200" />
        <button @click="saveHarvest" style="width:100%; padding:12px; border:none; border-radius:10px; background:#2D6A4F; color:#fff; font-family:inherit; font-size:14px; font-weight:600; cursor:pointer; margin-top:4px;">
          Save Harvest
        </button>
      </div>
    </AppModal>

  </div>
</template>

<script setup lang="ts">
import type { Farm, Field, Crop, Plantation } from '~/types'

definePageMeta({ layout: 'dashboard', middleware: ['auth'], ssr: false })
useHead({ title: 'WiseFarm — Plantations' })

const dark = useState('wf-dark', () => false)
const api = useApi()
const toast = useWfToast()

// ─── Farms (needed by field picker loader) ────────────────────────────────────
const apiFarms = ref<Farm[]>([])
const farmsLoading = ref(false)

/** Load all farms. Used internally by loadAllFieldsForPicker. */
const loadFarms = async () => {
  if (farmsLoading.value) return
  farmsLoading.value = true
  try {
    const res = await api.get<{ data: Farm[] }>('/farms')
    apiFarms.value = res.data
  } catch {
    // silent
  } finally {
    farmsLoading.value = false
  }
}

// ─── Crops ────────────────────────────────────────────────────────────────────
const apiCrops = ref<Crop[]>([])

/** Load all crop types for the plantation form dropdown. */
const loadCrops = async () => {
  if (apiCrops.value.length > 0) return
  try {
    const res = await api.get<{ data: Crop[] }>('/crops')
    apiCrops.value = res.data
  } catch {
    // silent
  }
}

// ─── Field picker ─────────────────────────────────────────────────────────────
const allFieldsForPicker = ref<Field[]>([])

/**
 * Populate the field picker by loading all farms and their fields.
 * No-ops when already populated.
 */
const loadAllFieldsForPicker = async () => {
  if (allFieldsForPicker.value.length > 0) return
  try {
    if (apiFarms.value.length === 0) await loadFarms()
    const arrays = await Promise.all(
      apiFarms.value.map((f: Farm) =>
        api.get<{ data: Field[] }>(`/farms/${f.id}/fields`).then(r => r.data).catch(() => [] as Field[])
      )
    )
    allFieldsForPicker.value = arrays.flat()
  } catch {
    // silent
  }
}

// ─── Plantations ──────────────────────────────────────────────────────────────
const selectedPlantationFieldId = ref<number | null>(null)
const apiPlantations = ref<Plantation[]>([])
const plantationsLoading = ref(false)
const showPlantationModal = ref(false)
const showHarvestModal = ref(false)
const editingPlantation = ref<Plantation | null>(null)
const plantationForm = reactive({ field_id: '', crop_id: '', planted_at: '', expected_harvest_at: '', area_ha: '', notes: '' })
const harvestForm = reactive({ harvested_at: '', yield_kg: '' })

/**
 * Derive the display label and colour for a plantation's status badge.
 *
 * @param p - Plantation record
 * @returns Object with label, color, and background colour
 */
const plantationStatus = (p: Plantation): { label: string; color: string; bg: string } => {
  if (p.harvested_at) return { label: 'Harvested', color: '#52B788', bg: 'rgba(82,183,136,0.15)' }
  if (p.expected_harvest_at && new Date(p.expected_harvest_at.slice(0, 10)) < new Date()) {
    return { label: 'Overdue', color: '#E76F51', bg: 'rgba(231,111,81,0.13)' }
  }
  return { label: 'Active', color: '#84A98C', bg: 'rgba(132,169,140,0.16)' }
}

/**
 * Format an ISO date string to a compact display string (e.g. "15 Jun 2026").
 *
 * @param iso - ISO date string
 * @returns Human-readable date
 */
const formatDate = (iso: string): string => {
  try {
    return new Date(iso).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' })
  } catch {
    return iso.slice(0, 10)
  }
}

/**
 * Load all plantations for a given field.
 *
 * @param fieldId - Field primary key
 */
const loadPlantations = async (fieldId: number) => {
  if (plantationsLoading.value) return
  plantationsLoading.value = true
  try {
    const res = await api.get<{ data: Plantation[] }>(`/fields/${fieldId}/plantations`)
    apiPlantations.value = res.data
  } catch {
    toast.show('Failed to load plantations.', 'error')
  } finally {
    plantationsLoading.value = false
  }
}

/** Open the new plantation modal, pre-selecting the current field. */
const openNewPlantation = () => {
  Object.assign(plantationForm, {
    field_id: selectedPlantationFieldId.value ? String(selectedPlantationFieldId.value) : '',
    crop_id: '',
    planted_at: '',
    expected_harvest_at: '',
    area_ha: '',
    notes: '',
  })
  showPlantationModal.value = true
}

/** POST the plantation form, reload list, close modal. */
const savePlantation = async () => {
  try {
    const fieldId = Number(plantationForm.field_id)
    await api.post(`/fields/${fieldId}/plantations`, {
      crop_id: Number(plantationForm.crop_id),
      planted_at: plantationForm.planted_at,
      expected_harvest_at: plantationForm.expected_harvest_at || null,
      area_ha: plantationForm.area_ha ? Number(plantationForm.area_ha) : null,
      notes: plantationForm.notes || null,
    })
    toast.show('Plantation created.', 'success')
    showPlantationModal.value = false
    if (selectedPlantationFieldId.value) await loadPlantations(selectedPlantationFieldId.value)
  } catch {
    toast.show('Something went wrong.', 'error')
  }
}

/**
 * Open the harvest modal for a specific plantation.
 *
 * @param p - Plantation to record harvest for
 */
const openRecordHarvest = (p: Plantation) => {
  editingPlantation.value = p
  Object.assign(harvestForm, { harvested_at: '', yield_kg: '' })
  showHarvestModal.value = true
}

/** PUT the harvest form fields onto the plantation, reload list, close modal. */
const saveHarvest = async () => {
  if (!editingPlantation.value) return
  if (!harvestForm.harvested_at) { toast.show('Please enter a harvest date.', 'error'); return }
  try {
    const p = editingPlantation.value
    await api.put(`/fields/${p.field_id}/plantations/${p.id}`, {
      harvested_at: harvestForm.harvested_at,
      yield_kg: harvestForm.yield_kg ? Number(harvestForm.yield_kg) : null,
    })
    toast.show('Harvest recorded.', 'success')
    showHarvestModal.value = false
    if (selectedPlantationFieldId.value) await loadPlantations(selectedPlantationFieldId.value)
  } catch {
    toast.show('Something went wrong.', 'error')
  }
}

/**
 * Delete a plantation after confirmation.
 *
 * @param p - Plantation to delete
 */
const deletePlantation = async (p: Plantation) => {
  if (!confirm('Delete this plantation?')) return
  try {
    await api.del(`/fields/${p.field_id}/plantations/${p.id}`)
    toast.show('Plantation deleted.', 'success')
    if (selectedPlantationFieldId.value) await loadPlantations(selectedPlantationFieldId.value)
  } catch {
    toast.show('Something went wrong.', 'error')
  }
}

watch(selectedPlantationFieldId, (v) => {
  if (v) loadPlantations(v)
  else apiPlantations.value = []
})

// ─── Static fallback ──────────────────────────────────────────────────────────
const staticPlantations = [
  { plot: 'Plot 01 · North Ridge',  crop: 'Wheat',  planted: '12 Nov 2025', harvest: '20 Jul 2026', pct: 82, status: 'Maturing',     sc: '#52B788', sb: 'rgba(132,169,140,0.16)' },
  { plot: 'Plot 04 · Riverside',    crop: 'Tomato', planted: '04 Apr 2026', harvest: '15 Aug 2026', pct: 48, status: 'Growing',      sc: '#84A98C', sb: 'rgba(132,169,140,0.16)' },
  { plot: 'Plot 07 · East Plateau', crop: 'Potato', planted: '21 Mar 2026', harvest: '02 Sep 2026', pct: 55, status: 'Growing',      sc: '#84A98C', sb: 'rgba(132,169,140,0.16)' },
  { plot: 'Plot 09 · Hillside',     crop: 'Grapes', planted: '15 Mar 2024', harvest: '28 Sep 2026', pct: 90, status: 'Ripening',     sc: '#2D6A4F', sb: 'rgba(132,169,140,0.16)' },
  { plot: 'Plot 12 · South Slope',  crop: 'Corn',   planted: '02 May 2026', harvest: '10 Oct 2026', pct: 33, status: 'Early growth', sc: '#F4A261', sb: 'rgba(132,169,140,0.16)' },
]

onMounted(async () => {
  await loadCrops()
  await loadAllFieldsForPicker()
})
</script>
