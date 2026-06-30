<template>
  <div :data-theme="dark ? 'dark' : 'light'" style="display:flex; flex-direction:column; gap:24px; min-width:0;">

    <!-- Page heading -->
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
      <div>
        <h1 style="font-size:22px; font-weight:800; margin:0 0 4px; letter-spacing:-0.4px;">Farm Dashboard</h1>
        <p style="font-size:14px; color:var(--muted,#73817a); margin:0;">Good morning, {{ firstName }} — here's your farm overview.</p>
      </div>
      <button style="display:flex; align-items:center; gap:8px; padding:10px 18px; border:1px solid var(--border,#e8e4d9); border-radius:10px; background:var(--card,#fff); color:var(--text,#1f2a24); font-family:inherit; font-size:14px; font-weight:600; cursor:pointer; transition:border-color .15s;" onmouseover="this.style.borderColor='#84A98C'" onmouseout="this.style.borderColor='var(--border,#e8e4d9)'">
        <i class="fa-solid fa-download" style="color:#84A98C;"></i> Export report
      </button>
    </div>

    <!-- KPI cards -->
    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px;">
      <div
        v-for="kpi in kpiDefs"
        :key="kpi.id"
        @click="activeKpi = kpi.id"
        :style="`background:var(--card,#fff); border:1.5px solid ${activeKpi === kpi.id ? kpi.color : 'var(--border,#e8e4d9)'}; border-radius:16px; padding:20px; cursor:pointer; transition:border-color .2s, box-shadow .2s; box-shadow:${activeKpi === kpi.id ? `0 4px 16px ${kpi.iconBg}` : 'none'}; animation:fadeUp .4s ease both;`"
      >
        <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:14px;">
          <div :style="`width:42px; height:42px; border-radius:12px; background:${kpi.iconBg}; display:flex; align-items:center; justify-content:center;`">
            <i :class="kpi.icon" :style="`color:${kpi.color}; font-size:17px;`"></i>
          </div>
          <span :style="`display:flex; align-items:center; gap:4px; font-size:12px; font-weight:600; color:${kpi.up ? '#52B788' : kpi.alert ? '#E76F51' : '#E76F51'}; padding:4px 8px; border-radius:99px; background:${kpi.up ? 'rgba(82,183,136,0.12)' : 'rgba(231,111,81,0.12)'};`">
            <i :class="kpi.trendIcon" style="font-size:10px;"></i>{{ kpi.trend }}
          </span>
        </div>
        <div style="font-size:28px; font-weight:800; letter-spacing:-0.8px; margin-bottom:4px;">{{ kpi.value }}<span v-if="kpi.unit" style="font-size:14px; font-weight:500; color:var(--muted,#73817a); margin-left:3px;">{{ kpi.unit }}</span></div>
        <div style="font-size:13px; color:var(--muted,#73817a); font-weight:500;">{{ kpi.label }}</div>
      </div>
    </div>

    <!-- Charts grid -->
    <div style="display:grid; grid-template-columns:1.4fr 1fr; gap:20px;">
      <!-- Line chart -->
      <div style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:18px; padding:22px;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:4px;">
          <div>
            <h3 style="font-size:15px; font-weight:700; margin:0 0 3px;">Soil Moisture — 24h</h3>
            <p style="font-size:13px; color:var(--muted,#73817a); margin:0;">Field A · North Ridge</p>
          </div>
          <div style="display:flex; align-items:center; gap:7px; font-size:13px; color:#52B788; font-weight:600;">
            <span style="width:8px; height:8px; border-radius:50%; background:#52B788; display:inline-block;"></span>62.4%
          </div>
        </div>
        <ClientOnly>
          <div style="position:relative; height:260px; margin-top:14px;">
            <canvas ref="lineCanvas"></canvas>
          </div>
        </ClientOnly>
      </div>
      <!-- Bar chart -->
      <div style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:18px; padding:22px;">
        <div style="margin-bottom:4px;">
          <h3 style="font-size:15px; font-weight:700; margin:0 0 3px;">Harvest Forecast</h3>
          <p style="font-size:13px; color:var(--muted,#73817a); margin:0;">Tonnes per crop · current season</p>
        </div>
        <ClientOnly>
          <div style="position:relative; height:260px; margin-top:14px;">
            <canvas ref="barCanvas"></canvas>
          </div>
        </ClientOnly>
      </div>
    </div>

    <!-- Tasks + Alerts -->
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
      <!-- Tasks -->
      <div style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:18px; padding:22px;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:18px;">
          <h3 style="font-size:15px; font-weight:700; margin:0;">Upcoming Tasks</h3>
          <span style="font-size:12px; color:#2D6A4F; font-weight:600; cursor:pointer;">View all</span>
        </div>
        <div style="display:flex; flex-direction:column; gap:12px;">
          <div v-for="task in tasks" :key="task.title" style="display:flex; align-items:center; gap:13px; padding:14px; border-radius:12px; background:var(--bg,#F4F1EA);">
            <div :style="`width:38px; height:38px; border-radius:11px; background:${task.iconBg}; display:flex; align-items:center; justify-content:center; flex-shrink:0;`">
              <i :class="task.icon" :style="`color:${task.color}; font-size:15px;`"></i>
            </div>
            <div style="flex:1; min-width:0;">
              <div style="font-size:14px; font-weight:600; margin-bottom:2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ task.title }}</div>
              <div style="font-size:12px; color:var(--muted,#73817a);">Due {{ task.due }}</div>
            </div>
            <span :style="`font-size:11.5px; font-weight:600; padding:4px 10px; border-radius:99px; background:${task.badgeBg}; color:${task.badgeColor}; flex-shrink:0;`">{{ task.status }}</span>
          </div>
        </div>
      </div>

      <!-- Alerts -->
      <div style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:18px; padding:22px;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:18px;">
          <h3 style="font-size:15px; font-weight:700; margin:0;">Active Alerts</h3>
          <span style="font-size:12px; padding:3px 9px; border-radius:99px; background:rgba(231,111,81,0.12); color:#E76F51; font-weight:600;">3 urgent</span>
        </div>
        <div style="display:flex; flex-direction:column; gap:10px;">
          <div v-for="alert in alerts" :key="alert.title" :style="`display:flex; align-items:flex-start; gap:12px; padding:13px 14px; border-radius:12px; background:${alert.bg};`">
            <div :style="`width:34px; height:34px; border-radius:9px; background:${alert.bg}; border:1px solid ${alert.color}33; display:flex; align-items:center; justify-content:center; flex-shrink:0;`">
              <i :class="alert.icon" :style="`color:${alert.color}; font-size:13px;`"></i>
            </div>
            <div style="flex:1; min-width:0;">
              <div style="font-size:13.5px; font-weight:600; margin-bottom:2px;">{{ alert.title }}</div>
              <div style="font-size:12px; color:var(--muted,#73817a);">{{ alert.desc }}</div>
            </div>
            <span style="font-size:11px; color:var(--muted,#73817a); flex-shrink:0;">{{ alert.time }}</span>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
import type { Farm } from '~/types'

definePageMeta({ layout: 'dashboard', middleware: ['auth'], ssr: false })
useHead({ title: 'WiseFarm — Dashboard' })

const dark = useState('wf-dark', () => false)
const auth = useAuthStore()
const api = useApi()

const firstName = computed(() => (auth.user?.name ?? 'there').split(' ')[0])

// ─── KPI cards ────────────────────────────────────────────────────────────────
const activeKpi = ref('alerts')

const kpiDefs = [
  { id: 'fields',   label: 'Total Fields',     value: '24',    unit: '',   icon: 'fa-solid fa-vector-square',        color: '#2D6A4F', iconBg: 'rgba(45,106,79,0.12)',   trend: '+2',  trendIcon: 'fa-solid fa-arrow-up',   up: true,  alert: false },
  { id: 'alerts',   label: 'Active Alerts',     value: '3',     unit: '',   icon: 'fa-solid fa-triangle-exclamation', color: '#E76F51', iconBg: 'rgba(231,111,81,0.12)', trend: '+1',  trendIcon: 'fa-solid fa-arrow-up',   up: false, alert: true  },
  { id: 'harvest',  label: "Today's Harvest",   value: '1,840', unit: 'kg', icon: 'fa-solid fa-tractor',              color: '#40916C', iconBg: 'rgba(64,145,108,0.12)', trend: '+8%', trendIcon: 'fa-solid fa-arrow-up',   up: true,  alert: false },
  { id: 'moisture', label: 'Soil Moisture Avg', value: '62',    unit: '%',  icon: 'fa-solid fa-droplet',              color: '#52B788', iconBg: 'rgba(82,183,136,0.14)', trend: '-3%', trendIcon: 'fa-solid fa-arrow-down', up: false, alert: false },
]

// ─── Tasks ────────────────────────────────────────────────────────────────────
const tasks = [
  { title: 'Irrigate Field A — North',     due: 'today, 16:00', status: 'Urgent',    icon: 'fa-solid fa-droplet',   color: '#2D6A4F', iconBg: 'rgba(45,106,79,0.12)',   badgeBg: 'rgba(231,111,81,0.14)',  badgeColor: '#E76F51' },
  { title: 'Fertilize Field B — South',    due: 'tomorrow',     status: 'Scheduled', icon: 'fa-solid fa-flask',     color: '#40916C', iconBg: 'rgba(64,145,108,0.12)', badgeBg: 'rgba(244,162,97,0.16)',  badgeColor: '#c4621f' },
  { title: 'Inspect IoT sensor cluster C', due: 'Jun 29',       status: 'Pending',   icon: 'fa-solid fa-microchip', color: '#84A98C', iconBg: 'rgba(132,169,140,0.18)', badgeBg: 'rgba(115,129,122,0.14)', badgeColor: '#73817a' },
  { title: 'Harvest tomato plot D',         due: 'Jun 30',       status: 'Done',      icon: 'fa-solid fa-check',     color: '#52B788', iconBg: 'rgba(82,183,136,0.16)', badgeBg: 'rgba(82,183,136,0.16)',  badgeColor: '#2f8a5f' },
]

// ─── Alerts ───────────────────────────────────────────────────────────────────
const alerts = [
  { title: 'Low soil moisture in Field X',   desc: 'Dropped to 38% — below 45% threshold',  icon: 'fa-solid fa-droplet-slash',           color: '#E76F51', bg: 'rgba(231,111,81,0.08)',  time: '5m'  },
  { title: 'Temperature drop expected',       desc: 'Forecast 4°C tonight — frost risk',      icon: 'fa-solid fa-temperature-arrow-down',  color: '#F4A261', bg: 'rgba(244,162,97,0.09)',  time: '22m' },
  { title: 'Sensor C-04 offline',             desc: 'No signal for 2h — check battery',       icon: 'fa-solid fa-plug-circle-xmark',       color: '#F4A261', bg: 'rgba(244,162,97,0.09)',  time: '1h'  },
  { title: 'Optimal harvest window',          desc: 'Grapes plot reaching peak ripeness',      icon: 'fa-solid fa-circle-check',            color: '#52B788', bg: 'rgba(82,183,136,0.09)', time: '3h'  },
]

// ─── Farms (for KPI context) ──────────────────────────────────────────────────
const apiFarms = ref<Farm[]>([])

/** Load the authenticated user's farms from the API for KPI context. */
const loadFarms = async () => {
  try {
    const res = await api.get<{ data: Farm[] }>('/farms')
    apiFarms.value = res.data
  } catch {
    // keep empty — static KPIs remain displayed
  }
}

// ─── Chart.js ─────────────────────────────────────────────────────────────────
const lineCanvas = ref<HTMLCanvasElement | null>(null)
const barCanvas  = ref<HTMLCanvasElement | null>(null)
let lineChart: any = null
let barChart:  any = null

/** Build or rebuild both Chart.js instances with current theme colours. */
const buildCharts = async () => {
  if (!lineCanvas.value || !barCanvas.value) return
  const { Chart, registerables } = await import('chart.js')
  Chart.register(...registerables)

  const tc = dark.value
    ? { grid: 'rgba(255,255,255,0.06)', tick: '#9aa39d' }
    : { grid: 'rgba(0,0,0,0.05)',       tick: '#73817a' }

  const hours: string[] = []
  const moist: number[] = []
  let base = 64
  for (let h = 0; h <= 24; h += 2) {
    hours.push((h < 10 ? '0' + h : h) + ':00')
    base += Math.sin(h / 3) * 4 + (Math.random() * 3 - 1.5) - (h > 12 ? 1.2 : 0)
    moist.push(Math.round(Math.max(48, Math.min(78, base)) * 10) / 10)
  }
  const ctxL = lineCanvas.value.getContext('2d')!
  const grad = ctxL.createLinearGradient(0, 0, 0, 260)
  grad.addColorStop(0, 'rgba(45,106,79,0.28)')
  grad.addColorStop(1, 'rgba(45,106,79,0.01)')
  if (lineChart) lineChart.destroy()
  lineChart = new Chart(ctxL, {
    type: 'line',
    data: {
      labels: hours,
      datasets: [{
        data: moist,
        borderColor: '#2D6A4F',
        backgroundColor: grad,
        fill: true,
        tension: 0.4,
        borderWidth: 2.5,
        pointRadius: 0,
      }],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { grid: { display: false }, ticks: { color: tc.tick, maxRotation: 0, maxTicksLimit: 7 } },
        y: { min: 40, max: 80, grid: { color: tc.grid }, ticks: { color: tc.tick, callback: (v: any) => v + '%' } },
      },
    },
  })

  if (barChart) barChart.destroy()
  barChart = new Chart(barCanvas.value.getContext('2d')!, {
    type: 'bar',
    data: {
      labels: ['Wheat', 'Corn', 'Tomato', 'Potato', 'Grapes'],
      datasets: [{
        data: [42, 58, 31, 47, 24],
        backgroundColor: ['#2D6A4F', '#52B788', '#84A98C', '#40916C', '#74C69D'],
        borderRadius: 7,
        maxBarThickness: 46,
      }],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { grid: { display: false }, ticks: { color: tc.tick } },
        y: { beginAtZero: true, grid: { color: tc.grid }, ticks: { color: tc.tick, callback: (v: any) => v + 't' } },
      },
    },
  })
}

onMounted(async () => {
  await loadFarms()
  await nextTick()
  buildCharts()
})

watch(dark, () => nextTick(() => buildCharts()))

onUnmounted(() => { lineChart?.destroy(); barChart?.destroy() })
</script>
