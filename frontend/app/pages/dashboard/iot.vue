<template>
  <div :data-theme="dark ? 'dark' : 'light'" style="display:flex; flex-direction:column; gap:24px; min-width:0;">

    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
      <div>
        <h1 style="font-size:22px; font-weight:800; margin:0 0 4px; letter-spacing:-0.4px;">IoT Sensors</h1>
        <p style="font-size:14px; color:var(--muted,#73817a); margin:0;">{{ sensors.filter(s => s.online).length }} online · {{ sensors.filter(s => !s.online).length }} offline</p>
      </div>
      <button style="display:flex; align-items:center; gap:8px; padding:10px 18px; border:none; border-radius:10px; background:#2D6A4F; color:#fff; font-family:inherit; font-size:14px; font-weight:600; cursor:pointer;">
        <i class="fa-solid fa-plus"></i> Add sensor
      </button>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:16px;">
      <div v-for="sensor in sensors" :key="sensor.id" style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:16px; padding:20px;">
        <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:16px;">
          <div style="display:flex; align-items:center; gap:11px;">
            <div style="width:40px; height:40px; border-radius:11px; background:rgba(45,106,79,0.10); display:flex; align-items:center; justify-content:center;">
              <i :class="sensor.icon" style="color:#2D6A4F; font-size:16px;"></i>
            </div>
            <div>
              <div style="font-size:14px; font-weight:700; font-family:'JetBrains Mono',monospace;">{{ sensor.id }}</div>
              <div style="font-size:12px; color:var(--muted,#73817a);">{{ sensor.type }} · {{ sensor.loc }}</div>
            </div>
          </div>
          <span :style="`font-size:11.5px; font-weight:600; padding:4px 10px; border-radius:99px; background:${sensor.statusBg}; color:${sensor.statusColor};`">{{ sensor.statusLabel }}</span>
        </div>
        <div style="text-align:center; margin:10px 0 16px;">
          <span :style="`font-size:34px; font-weight:800; letter-spacing:-1px; color:${sensor.valueColor}; font-family:'JetBrains Mono',monospace;`">{{ sensor.value }}</span>
          <span style="font-size:16px; color:var(--muted,#73817a); font-weight:500; margin-left:3px;">{{ sensor.unit }}</span>
        </div>
        <div>
          <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
            <span style="font-size:12px; color:var(--muted,#73817a);">Battery</span>
            <span :style="`font-size:12px; font-weight:600; color:${sensor.battColor};`">{{ sensor.battery }}%</span>
          </div>
          <div style="height:5px; background:var(--bg,#F4F1EA); border-radius:99px; overflow:hidden;">
            <div :style="sensor.battStyle"></div>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'dashboard', middleware: ['auth'], ssr: false })
useHead({ title: 'WiseFarm — IoT Sensors' })

const dark = useState('wf-dark', () => false)

// ─── Static sensor data (M3 milestone — API integration pending) ──────────────
const sensors = [
  { id: 'SM-A01', type: 'Soil Moisture', icon: 'fa-solid fa-droplet',         loc: 'Field A', value: '72',   unit: '%',   battery: 88, online: true  },
  { id: 'TM-A02', type: 'Temperature',   icon: 'fa-solid fa-temperature-half', loc: 'Field A', value: '23.4', unit: '°C',  battery: 64, online: true  },
  { id: 'SM-C04', type: 'Soil Moisture', icon: 'fa-solid fa-droplet',         loc: 'Field C', value: '—',    unit: '',    battery: 11, online: false },
  { id: 'PH-D01', type: 'Soil pH',       icon: 'fa-solid fa-flask-vial',       loc: 'Field D', value: '6.8',  unit: 'pH',  battery: 91, online: true  },
  { id: 'HU-E03', type: 'Humidity',      icon: 'fa-solid fa-cloud',            loc: 'Field E', value: '54',   unit: '%',   battery: 73, online: true  },
  { id: 'LX-B02', type: 'Light',         icon: 'fa-solid fa-sun',              loc: 'Field B', value: '48k',  unit: 'lux', battery: 80, online: true  },
].map(s => ({
  ...s,
  statusColor:  s.online ? '#52B788' : '#E76F51',
  statusBg:     s.online ? 'rgba(82,183,136,0.15)' : 'rgba(231,111,81,0.13)',
  statusLabel:  s.online ? 'Online' : 'Offline',
  battColor:    s.battery < 20 ? '#E76F51' : s.battery < 50 ? '#F4A261' : '#52B788',
  battStyle:    `width:${s.battery}%; height:100%; border-radius:99px; background:${s.battery < 20 ? '#E76F51' : s.battery < 50 ? '#F4A261' : '#52B788'};`,
  valueColor:   s.online ? 'var(--text,#1f2a24)' : '#E76F51',
}))
</script>
