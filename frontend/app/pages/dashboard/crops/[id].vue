<template>
  <div :data-theme="dark ? 'dark' : 'light'" style="display:flex; flex-direction:column; gap:24px; min-width:0;">

    <!-- Back -->
    <div>
      <NuxtLink to="/dashboard/crops" style="font-size:13px; color:var(--muted,#73817a); text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
        <i class="fa-solid fa-arrow-left" style="font-size:11px;"></i> Back to Crops
      </NuxtLink>
    </div>

    <div v-if="loading" style="font-size:14px; color:var(--muted,#73817a);">Loading…</div>

    <div v-else-if="crop">

      <!-- Header -->
      <div style="display:flex; align-items:center; gap:16px; margin-bottom:24px;">
        <div style="width:56px; height:56px; border-radius:16px; background:rgba(45,106,79,0.10); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
          <i :class="crop.icon" style="color:#2D6A4F; font-size:24px;"></i>
        </div>
        <div>
          <h1 style="font-size:22px; font-weight:800; margin:0 0 2px; letter-spacing:-0.4px;">{{ crop.name }}</h1>
          <div v-if="crop.scientific_name" style="font-size:14px; color:var(--muted,#73817a); font-style:italic;">{{ crop.scientific_name }}</div>
        </div>
        <span style="margin-left:auto; font-size:12px; font-weight:600; padding:5px 13px; border-radius:99px; background:rgba(132,169,140,0.15); color:#52635a; text-transform:capitalize;">{{ crop.category }}</span>
      </div>

      <!-- Description -->
      <div v-if="profile?.description" style="font-size:14px; color:var(--muted,#73817a); line-height:1.6; margin-bottom:24px;">
        {{ profile.description }}
      </div>

      <!-- Growing conditions -->
      <div v-if="profile" style="margin-bottom:24px;">
        <h2 style="font-size:15px; font-weight:700; margin:0 0 14px;">Growing Conditions</h2>
        <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:12px;">
          <div v-if="profile.optimal_temp_min != null" style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:12px; padding:14px;">
            <div style="font-size:11px; color:var(--muted,#73817a); font-weight:600; text-transform:uppercase; letter-spacing:.4px; margin-bottom:6px;">Temperature</div>
            <div style="font-size:17px; font-weight:700;">{{ profile.optimal_temp_min }}–{{ profile.optimal_temp_max }} °C</div>
          </div>
          <div v-if="profile.ph_min != null" style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:12px; padding:14px;">
            <div style="font-size:11px; color:var(--muted,#73817a); font-weight:600; text-transform:uppercase; letter-spacing:.4px; margin-bottom:6px;">Soil pH</div>
            <div style="font-size:17px; font-weight:700;">{{ profile.ph_min }}–{{ profile.ph_max }}</div>
          </div>
          <div v-if="profile.annual_rainfall_min != null" style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:12px; padding:14px;">
            <div style="font-size:11px; color:var(--muted,#73817a); font-weight:600; text-transform:uppercase; letter-spacing:.4px; margin-bottom:6px;">Rainfall</div>
            <div style="font-size:17px; font-weight:700;">{{ profile.annual_rainfall_min }}–{{ profile.annual_rainfall_max }} mm</div>
          </div>
          <div v-if="profile.drought_tolerance" style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:12px; padding:14px;">
            <div style="font-size:11px; color:var(--muted,#73817a); font-weight:600; text-transform:uppercase; letter-spacing:.4px; margin-bottom:6px;">Drought</div>
            <div :style="`font-size:15px; font-weight:700; text-transform:capitalize; color:${toleranceColor(profile.drought_tolerance)}`">{{ profile.drought_tolerance }}</div>
          </div>
          <div v-if="profile.frost_tolerance" style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:12px; padding:14px;">
            <div style="font-size:11px; color:var(--muted,#73817a); font-weight:600; text-transform:uppercase; letter-spacing:.4px; margin-bottom:6px;">Frost</div>
            <div :style="`font-size:15px; font-weight:700; text-transform:capitalize; color:${toleranceColor(profile.frost_tolerance)}`">{{ profile.frost_tolerance }}</div>
          </div>
          <div v-if="crop.avg_growth_days" style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:12px; padding:14px;">
            <div style="font-size:11px; color:var(--muted,#73817a); font-weight:600; text-transform:uppercase; letter-spacing:.4px; margin-bottom:6px;">Avg growth</div>
            <div style="font-size:17px; font-weight:700;">{{ crop.avg_growth_days }} <span style="font-size:12px; font-weight:500; color:var(--muted,#73817a);">days</span></div>
          </div>
        </div>
      </div>

      <!-- Soil suitability -->
      <div v-if="crop.soil_suitabilities?.length" style="margin-bottom:24px;">
        <h2 style="font-size:15px; font-weight:700; margin:0 0 14px;">Soil Suitability</h2>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
          <div
            v-for="s in crop.soil_suitabilities"
            :key="s.soil_type"
            style="display:flex; align-items:center; gap:8px; background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:12px; padding:10px 16px;"
          >
            <span style="font-size:14px; font-weight:600; text-transform:capitalize;">{{ s.soil_type }}</span>
            <span :style="`font-size:12px; font-weight:600; padding:3px 9px; border-radius:99px; background:${suitabilityBg(s.suitability)}; color:${suitabilityColor(s.suitability)};`">
              {{ s.suitability }}
            </span>
          </div>
        </div>
      </div>

      <!-- Yield benchmarks -->
      <div v-if="crop.yield_benchmarks?.length" style="margin-bottom:24px;">
        <h2 style="font-size:15px; font-weight:700; margin:0 0 14px;">Global Yield Benchmarks (FAOSTAT)</h2>
        <div style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:14px; padding:20px; overflow-x:auto;">
          <div style="display:flex; align-items:flex-end; gap:8px; height:120px; min-width:400px;">
            <div
              v-for="b in crop.yield_benchmarks"
              :key="b.year"
              style="flex:1; display:flex; flex-direction:column; align-items:center; gap:4px;"
            >
              <div style="font-size:11px; color:var(--muted,#73817a); font-weight:600;">{{ formatYield(b.yield_kg_ha) }}</div>
              <div
                :style="`width:100%; border-radius:6px 6px 0 0; background:#2D6A4F; height:${barHeight(b.yield_kg_ha)}px; min-height:4px;`"
              ></div>
              <div style="font-size:11px; color:var(--muted,#73817a);">{{ b.year }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Tips -->
      <div v-if="tips.length">
        <h2 style="font-size:15px; font-weight:700; margin:0 0 14px;">Agronomist Tips</h2>
        <div style="display:flex; flex-direction:column; gap:10px;">
          <div
            v-for="tip in tips"
            :key="tip.id"
            style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:12px; padding:14px 18px;"
          >
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
              <i :class="tipIcon(tip.type)" style="color:#2D6A4F; font-size:13px; width:16px;"></i>
              <span style="font-size:12px; font-weight:600; text-transform:capitalize; color:var(--muted,#73817a);">{{ tip.type }}</span>
              <span v-if="tip.soil_type" style="font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px; background:rgba(132,169,140,0.15); color:#52635a; text-transform:capitalize; margin-left:4px;">{{ tip.soil_type }}</span>
            </div>
            <p style="font-size:14px; margin:0; line-height:1.55;">{{ tip.body }}</p>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import type { Crop, CropProfile, CropTip } from '~/types'

definePageMeta({ layout: 'dashboard', middleware: ['auth'], ssr: false })

const route = useRoute()
const dark = useState('wf-dark', () => false)
const api = useApi()

const crop = ref<Crop | null>(null)
const tips = ref<CropTip[]>([])
const loading = ref(true)

const profile = computed<CropProfile | null>(() => crop.value?.profile ?? null)

useHead(() => ({ title: crop.value ? `WiseFarm — ${crop.value.name}` : 'WiseFarm — Crop' }))

const barHeight = (val: number) => {
  if (!crop.value?.yield_benchmarks?.length) return 4
  const max = Math.max(...crop.value.yield_benchmarks.map(b => b.yield_kg_ha))
  return max > 0 ? Math.round((val / max) * 80) : 4
}

const formatYield = (val: number) => {
  if (val >= 1000) return (val / 1000).toFixed(1) + 't'
  return val.toFixed(0) + 'kg'
}

const toleranceColor = (v: string) => v === 'high' ? '#2D6A4F' : v === 'medium' ? '#F4A261' : '#e05252'

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

const tipIcon = (type: string) => {
  const map: Record<string, string> = {
    planting: 'fa-solid fa-seedling', harvesting: 'fa-solid fa-wheat-awn',
    soil: 'fa-solid fa-layer-group', irrigation: 'fa-solid fa-droplet',
    pest: 'fa-solid fa-bug', general: 'fa-solid fa-circle-info',
  }
  return map[type] ?? 'fa-solid fa-circle-info'
}

onMounted(async () => {
  try {
    const [cropRes, tipsRes] = await Promise.all([
      api.get<{ data: Crop }>(`/crops/${route.params.id}`),
      api.get<{ data: CropTip[] }>(`/crops/${route.params.id}/tips`),
    ])
    crop.value = cropRes.data
    tips.value = tipsRes.data
  } finally {
    loading.value = false
  }
})
</script>
