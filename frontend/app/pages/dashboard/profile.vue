<template>
  <div :data-theme="dark ? 'dark' : 'light'" style="display:flex; flex-direction:column; gap:24px; min-width:0;">

    <div>
      <h1 style="font-size:22px; font-weight:800; margin:0 0 4px; letter-spacing:-0.4px;">Profile</h1>
      <p style="font-size:14px; color:var(--muted,#73817a); margin:0;">Your account details and recent activity.</p>
    </div>

    <div style="display:grid; grid-template-columns:340px 1fr; gap:20px; align-items:start;">
      <!-- Profile card -->
      <div style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:18px; padding:28px; text-align:center;">
        <div style="width:72px; height:72px; border-radius:50%; background:#2D6A4F; display:flex; align-items:center; justify-content:center; font-size:26px; font-weight:800; color:#fff; margin:0 auto 16px;">{{ profile.initials }}</div>
        <div style="font-size:18px; font-weight:800; margin-bottom:4px;">{{ profile.name }}</div>
        <div style="font-size:14px; color:var(--muted,#73817a); margin-bottom:20px;">{{ profile.role }}</div>
        <!-- Stats -->
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:24px;">
          <div v-for="s in profile.stats" :key="s.label" style="background:var(--bg,#F4F1EA); border-radius:12px; padding:14px;">
            <div style="font-size:20px; font-weight:800; color:#2D6A4F; margin-bottom:3px;">{{ s.value }}</div>
            <div style="font-size:11.5px; color:var(--muted,#73817a);">{{ s.label }}</div>
          </div>
        </div>
        <button style="width:100%; padding:11px; border:none; border-radius:10px; background:#2D6A4F; color:#fff; font-family:inherit; font-size:14px; font-weight:600; cursor:pointer;">Edit profile</button>
        <button @click="handleLogout" style="width:100%; margin-top:8px; padding:11px; border:1.5px solid #e0dccf; border-radius:10px; background:transparent; color:#E76F51; font-family:inherit; font-size:14px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px;">
          <i class="fa-solid fa-arrow-right-from-bracket"></i> Sign out
        </button>
      </div>

      <!-- Details + activity -->
      <div style="display:flex; flex-direction:column; gap:16px;">
        <div style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:18px; padding:24px;">
          <h3 style="font-size:15px; font-weight:700; margin:0 0 18px;">Contact details</h3>
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
            <div v-for="detail in profile.details" :key="detail.label" style="display:flex; align-items:center; gap:12px;">
              <div style="width:36px; height:36px; border-radius:10px; background:rgba(45,106,79,0.10); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i :class="detail.icon" style="color:#2D6A4F; font-size:13px;"></i>
              </div>
              <div>
                <div style="font-size:11px; color:var(--muted,#73817a); font-weight:600; text-transform:uppercase; letter-spacing:.4px; margin-bottom:2px;">{{ detail.label }}</div>
                <div style="font-size:13.5px; font-weight:500;">{{ detail.value }}</div>
              </div>
            </div>
          </div>
        </div>
        <div style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:18px; padding:24px;">
          <h3 style="font-size:15px; font-weight:700; margin:0 0 18px;">Recent activity</h3>
          <div v-if="profile.activity.length === 0" style="font-size:14px; color:var(--muted,#73817a);">No recent activity.</div>
          <div v-else style="display:flex; flex-direction:column; gap:14px;">
            <div v-for="act in profile.activity" :key="act.text" style="display:flex; align-items:flex-start; gap:12px;">
              <div :style="`width:34px; height:34px; border-radius:9px; background:${act.col}18; display:flex; align-items:center; justify-content:center; flex-shrink:0;`">
                <i :class="act.icon" :style="`color:${act.col}; font-size:13px;`"></i>
              </div>
              <div style="flex:1;">
                <div style="font-size:13.5px; font-weight:500; margin-bottom:2px;">{{ act.text }}</div>
                <div style="font-size:12px; color:var(--muted,#73817a);">{{ act.time }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
import type { Farm } from '~/types'

definePageMeta({ layout: 'dashboard', middleware: ['auth'], ssr: false })
useHead({ title: 'WiseFarm — Profile' })

const dark = useState('wf-dark', () => false)
const auth = useAuthStore()
const api = useApi()

/** Log the user out and redirect to /auth. */
const handleLogout = () => auth.logout()

// ─── Farms (for profile stats) ────────────────────────────────────────────────
const apiFarms = ref<Farm[]>([])

/** Load farms to compute profile statistics. */
const loadFarms = async () => {
  try {
    const res = await api.get<{ data: Farm[] }>('/farms')
    apiFarms.value = res.data
  } catch {
    // keep empty
  }
}

/** Derived profile data from the authenticated user and loaded farms. */
const profile = computed(() => {
  const u = auth.user
  const displayName = u?.name ?? 'User'
  const initials = displayName.split(' ').map((w: string) => w[0]).join('').toUpperCase().slice(0, 2)
  return {
    name: displayName,
    initials,
    role: 'Agronomist',
    stats: [
      { label: 'Fields managed', value: String(apiFarms.value.reduce((s: number, f: Farm) => s + (f.fields_count ?? 0), 0)) },
      { label: 'Farms', value: String(apiFarms.value.length) },
    ],
    details: [
      { label: 'Email', value: u?.email ?? '—', icon: 'fa-regular fa-envelope' },
      { label: 'Role',  value: 'Agronomist',    icon: 'fa-solid fa-user-shield' },
    ],
    activity: [] as { icon: string; col: string; text: string; time: string }[],
  }
})

onMounted(() => loadFarms())
</script>
