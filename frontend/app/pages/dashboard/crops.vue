<template>
  <div :data-theme="dark ? 'dark' : 'light'" style="display:flex; flex-direction:column; gap:24px; min-width:0;">

    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
      <div>
        <h1 style="font-size:22px; font-weight:800; margin:0 0 4px; letter-spacing:-0.4px;">Crops</h1>
        <p style="font-size:14px; color:var(--muted,#73817a); margin:0;">
          {{ apiCrops.length > 0 ? apiCrops.length + ' crop types in the catalogue.' : 'Crop reference catalogue.' }}
        </p>
      </div>
    </div>

    <div v-if="cropsLoading" style="font-size:14px; color:var(--muted,#73817a); padding:20px 0;">Loading crops…</div>

    <!-- API crops -->
    <div v-else-if="apiCrops.length > 0" style="display:grid; grid-template-columns:repeat(auto-fill,minmax(300px,1fr)); gap:16px;">
      <div v-for="crop in apiCrops" :key="crop.id" style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:16px; padding:20px;">
        <div style="display:flex; align-items:center; gap:13px; margin-bottom:16px;">
          <div style="width:44px; height:44px; border-radius:13px; background:rgba(45,106,79,0.10); display:flex; align-items:center; justify-content:center;">
            <i :class="crop.icon" style="color:#2D6A4F; font-size:19px;"></i>
          </div>
          <div style="flex:1;">
            <div style="font-size:16px; font-weight:700;">{{ crop.name }}</div>
            <div v-if="crop.scientific_name" style="font-size:12px; color:var(--muted,#73817a); font-style:italic;">{{ crop.scientific_name }}</div>
          </div>
          <span style="font-size:11.5px; font-weight:600; padding:4px 10px; border-radius:99px; background:rgba(132,169,140,0.15); color:#52635a; text-transform:capitalize;">{{ crop.category }}</span>
        </div>
        <div v-if="crop.avg_growth_days" style="background:var(--bg,#F4F1EA); border-radius:10px; padding:12px;">
          <div style="font-size:11px; color:var(--muted,#73817a); font-weight:600; text-transform:uppercase; letter-spacing:.4px; margin-bottom:4px;">Avg growth</div>
          <div style="font-size:16px; font-weight:700;">{{ crop.avg_growth_days }} <span style="font-size:12px; font-weight:500; color:var(--muted,#73817a);">days</span></div>
        </div>
      </div>
    </div>

    <!-- Static fallback -->
    <div v-else style="display:grid; grid-template-columns:repeat(auto-fill,minmax(300px,1fr)); gap:16px;">
      <div v-for="crop in staticCrops" :key="crop.name" style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:16px; padding:20px;">
        <div style="display:flex; align-items:center; gap:13px; margin-bottom:16px;">
          <div style="width:44px; height:44px; border-radius:13px; background:rgba(45,106,79,0.10); display:flex; align-items:center; justify-content:center;">
            <i :class="crop.icon" style="color:#2D6A4F; font-size:19px;"></i>
          </div>
          <div>
            <div style="font-size:16px; font-weight:700;">{{ crop.name }}</div>
            <div style="font-size:12.5px; color:var(--muted,#73817a);">{{ crop.stage }}</div>
          </div>
          <span :style="`margin-left:auto; font-size:12px; font-weight:600; padding:4px 10px; border-radius:99px; background:${crop.hb}; color:${crop.hc};`">{{ crop.health }}</span>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:14px;">
          <div style="background:var(--bg,#F4F1EA); border-radius:10px; padding:12px;">
            <div style="font-size:11px; color:var(--muted,#73817a); font-weight:600; text-transform:uppercase; letter-spacing:.4px; margin-bottom:4px;">Area</div>
            <div style="font-size:16px; font-weight:700;">{{ crop.area }}</div>
          </div>
          <div style="background:var(--bg,#F4F1EA); border-radius:10px; padding:12px;">
            <div style="font-size:11px; color:var(--muted,#73817a); font-weight:600; text-transform:uppercase; letter-spacing:.4px; margin-bottom:4px;">Est. yield</div>
            <div style="font-size:16px; font-weight:700;">{{ crop.yield }}</div>
          </div>
        </div>
        <div>
          <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
            <span style="font-size:13px; color:var(--muted,#73817a);">Growth progress</span>
            <span style="font-size:13px; font-weight:700; color:#2D6A4F;">{{ crop.pct }}%</span>
          </div>
          <div style="height:6px; background:var(--bg,#F4F1EA); border-radius:99px; overflow:hidden;">
            <div :style="`width:${crop.pct}%; height:100%; border-radius:99px; background:#2D6A4F;`"></div>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
import type { Crop } from '~/types'

definePageMeta({ layout: 'dashboard', middleware: ['auth'], ssr: false })
useHead({ title: 'WiseFarm — Crops' })

const dark = useState('wf-dark', () => false)
const api = useApi()

// ─── API: Crops ───────────────────────────────────────────────────────────────
const apiCrops = ref<Crop[]>([])
const cropsLoading = ref(false)

/** Load all crop types from the catalogue. No-ops when already loaded. */
const loadCrops = async () => {
  if (cropsLoading.value || apiCrops.value.length > 0) return
  cropsLoading.value = true
  try {
    const res = await api.get<{ data: Crop[] }>('/crops')
    apiCrops.value = res.data
  } catch {
    // silently fall back to static crops
  } finally {
    cropsLoading.value = false
  }
}

// ─── Static fallback data ─────────────────────────────────────────────────────
const staticCrops = [
  { name: 'Wheat',  icon: 'fa-solid fa-wheat-awn',                     stage: 'Grain filling', pct: 78, area: '64 ha', yield: '42 t', health: 'Good',      hc: '#52B788', hb: 'rgba(82,183,136,0.15)'  },
  { name: 'Corn',   icon: 'fa-solid fa-seedling',                      stage: 'Vegetative',    pct: 45, area: '58 ha', yield: '58 t', health: 'Good',      hc: '#52B788', hb: 'rgba(82,183,136,0.15)'  },
  { name: 'Tomato', icon: 'fa-solid fa-apple-whole',                   stage: 'Flowering',     pct: 60, area: '22 ha', yield: '31 t', health: 'Monitor',   hc: '#F4A261', hb: 'rgba(244,162,97,0.15)'  },
  { name: 'Potato', icon: 'fa-solid fa-carrot',                        stage: 'Tuber growth',  pct: 70, area: '47 ha', yield: '47 t', health: 'Good',      hc: '#52B788', hb: 'rgba(82,183,136,0.15)'  },
  { name: 'Grapes', icon: 'fa-solid fa-wine-bottle',                   stage: 'Veraison',      pct: 85, area: '34 ha', yield: '24 t', health: 'Excellent', hc: '#2D6A4F', hb: 'rgba(45,106,79,0.13)'   },
  { name: 'Barley', icon: 'fa-solid fa-wheat-awn-circle-exclamation',  stage: 'Heading',       pct: 52, area: '40 ha', yield: '36 t', health: 'Good',      hc: '#52B788', hb: 'rgba(82,183,136,0.15)'  },
]

onMounted(() => loadCrops())
</script>
