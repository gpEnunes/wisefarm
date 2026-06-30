<template>
  <div :data-theme="dark ? 'dark' : 'light'" style="display:flex; flex-direction:column; gap:24px; min-width:0;">

    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
      <div>
        <h1 style="font-size:22px; font-weight:800; margin:0 0 4px; letter-spacing:-0.4px;">Reports</h1>
        <p style="font-size:14px; color:var(--muted,#73817a); margin:0;">Generated analytics and summaries.</p>
      </div>
      <button style="display:flex; align-items:center; gap:8px; padding:10px 18px; border:none; border-radius:10px; background:#2D6A4F; color:#fff; font-family:inherit; font-size:14px; font-weight:600; cursor:pointer;">
        <i class="fa-solid fa-plus"></i> New report
      </button>
    </div>

    <!-- Stats row -->
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px;">
      <div v-for="rs in reportStats" :key="rs.label" style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:14px; padding:20px; display:flex; align-items:center; gap:14px;">
        <div style="width:42px; height:42px; border-radius:12px; background:rgba(45,106,79,0.10); display:flex; align-items:center; justify-content:center;">
          <i :class="rs.icon" style="color:#2D6A4F; font-size:17px;"></i>
        </div>
        <div>
          <div style="font-size:24px; font-weight:800; letter-spacing:-0.5px;">{{ rs.value }}</div>
          <div style="font-size:13px; color:var(--muted,#73817a);">{{ rs.label }}</div>
        </div>
      </div>
    </div>

    <!-- Report list -->
    <div style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:18px; overflow:hidden;">
      <div v-for="(report, i) in reports" :key="report.name" :style="`display:flex; align-items:center; gap:16px; padding:18px 22px; ${i < reports.length - 1 ? 'border-bottom:1px solid var(--border,#e8e4d9);' : ''} transition:background .15s;`" onmouseover="this.style.background='var(--hover,#f1f5f1)'" onmouseout="this.style.background='transparent'">
        <div :style="`width:42px; height:42px; border-radius:12px; background:${report.col}18; display:flex; align-items:center; justify-content:center; flex-shrink:0;`">
          <i :class="report.icon" :style="`color:${report.col}; font-size:17px;`"></i>
        </div>
        <div style="flex:1;">
          <div style="font-size:14.5px; font-weight:600; margin-bottom:2px;">{{ report.name }}</div>
          <div style="font-size:12.5px; color:var(--muted,#73817a);">{{ report.type }} · {{ report.date }} · {{ report.size }}</div>
        </div>
        <div style="display:flex; gap:8px;">
          <button style="padding:7px 14px; border:1px solid var(--border,#e8e4d9); border-radius:8px; background:transparent; color:var(--text,#1f2a24); font-family:inherit; font-size:13px; font-weight:500; cursor:pointer; display:flex; align-items:center; gap:6px;">
            <i class="fa-solid fa-download" style="font-size:12px;"></i> Download
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'dashboard', middleware: ['auth'], ssr: false })
useHead({ title: 'WiseFarm — Reports' })

const dark = useState('wf-dark', () => false)

const reports = [
  { name: 'Monthly Yield Summary',    type: 'Yield',   date: 'Jun 2026',     size: '2.4 MB', icon: 'fa-solid fa-chart-pie',   col: '#2D6A4F' },
  { name: 'Irrigation Efficiency Q2', type: 'Water',   date: 'Jun 24, 2026', size: '1.1 MB', icon: 'fa-solid fa-droplet',     col: '#52B788' },
  { name: 'Soil Health Analysis',     type: 'Soil',    date: 'Jun 18, 2026', size: '3.7 MB', icon: 'fa-solid fa-layer-group', col: '#84A98C' },
  { name: 'Cost & Margin Report',     type: 'Finance', date: 'Jun 10, 2026', size: '0.9 MB', icon: 'fa-solid fa-coins',       col: '#F4A261' },
  { name: 'Pest & Disease Log',       type: 'Health',  date: 'Jun 02, 2026', size: '1.5 MB', icon: 'fa-solid fa-bug',         col: '#E76F51' },
]

const reportStats = [
  { label: 'Reports generated', value: '148', icon: 'fa-solid fa-file-lines'   },
  { label: 'This month',        value: '12',  icon: 'fa-solid fa-calendar-day' },
  { label: 'Scheduled',         value: '4',   icon: 'fa-solid fa-clock'        },
]
</script>
