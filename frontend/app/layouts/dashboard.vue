<template>
  <div
    :data-theme="dark ? 'dark' : 'light'"
    style="display:flex; height:100vh; width:100%; overflow:hidden; font-family:'Inter',system-ui,sans-serif; background:var(--bg,#F4F1EA); color:var(--text,#1f2a24);"
  >
    <!-- Mobile scrim -->
    <div
      v-if="isMobile && sidebarOpen"
      @click="sidebarOpen = false"
      style="position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:20;"
    ></div>

    <!-- Sidebar -->
    <aside :style="sidebarStyle">
      <!-- Logo -->
      <div style="padding:24px 20px 20px; flex-shrink:0;">
        <div style="display:flex; align-items:center; gap:10px;">
          <div style="width:36px; height:36px; border-radius:11px; background:rgba(255,255,255,0.18); display:flex; align-items:center; justify-content:center;">
            <i class="fa-solid fa-leaf" style="color:#fff; font-size:16px;"></i>
          </div>
          <span style="font-size:17px; font-weight:800; color:#fff; letter-spacing:-0.3px;">WiseFarm</span>
        </div>
      </div>

      <!-- Nav items -->
      <nav style="flex:1; padding:8px 12px; display:flex; flex-direction:column; gap:3px; overflow-y:auto;">
        <NuxtLink
          v-for="item in navDefs"
          :key="item.key"
          :to="`/dashboard${item.key === 'dashboard' ? '' : '/' + item.key}`"
          :style="linkStyle(isActive(item.key))"
          @click="closeOnMobile"
        >
          <i :class="item.icon" style="font-size:15px; width:17px; text-align:center;"></i>
          <span style="flex:1; text-align:left;">{{ item.label }}</span>
          <span v-if="item.badge" style="font-size:11px; font-weight:700; padding:2px 7px; border-radius:99px; background:#E76F51; color:#fff;">{{ item.badge }}</span>
        </NuxtLink>
      </nav>

      <!-- Farm info card -->
      <div style="padding:12px 12px 4px; flex-shrink:0;">
        <div style="background:rgba(255,255,255,0.10); border-radius:14px; padding:16px;">
          <div style="display:flex; align-items:center; gap:10px; margin-bottom:12px;">
            <div style="width:38px; height:38px; border-radius:10px; background:#52B788; display:flex; align-items:center; justify-content:center; font-size:16px; font-weight:800; color:#fff;">G</div>
            <div>
              <div style="font-size:13.5px; font-weight:700; color:#fff;">Green Valley Farm</div>
              <div style="font-size:12px; color:rgba(255,255,255,0.55);">Pro plan</div>
            </div>
          </div>
          <div style="height:5px; background:rgba(255,255,255,0.12); border-radius:99px; overflow:hidden;">
            <div style="width:68%; height:100%; background:#52B788; border-radius:99px;"></div>
          </div>
          <div style="display:flex; justify-content:space-between; margin-top:6px;">
            <span style="font-size:11px; color:rgba(255,255,255,0.45);">Sensors used</span>
            <span style="font-size:11px; color:rgba(255,255,255,0.65); font-weight:600;">34 / 50</span>
          </div>
        </div>
      </div>

      <!-- Logout -->
      <div style="padding:8px 12px 16px; flex-shrink:0;">
        <button
          @click="handleLogout"
          style="width:100%; display:flex; align-items:center; gap:12px; padding:11px 14px; border-radius:11px; cursor:pointer; background:transparent; border:none; font-family:inherit; font-size:14px; font-weight:500; color:rgba(255,255,255,0.55); transition:background .15s, color .15s;"
          onmouseover="this.style.background='rgba(231,111,81,0.18)'; this.style.color='#E76F51'"
          onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.55)'"
        >
          <i class="fa-solid fa-arrow-right-from-bracket" style="font-size:15px; width:17px; text-align:center;"></i>
          <span>Sign out</span>
        </button>
      </div>
    </aside>

    <!-- Main content area -->
    <div style="flex:1; display:flex; flex-direction:column; min-width:0;">

      <!-- Header -->
      <header style="height:60px; flex-shrink:0; background:var(--topbar,#fff); border-bottom:1px solid var(--border,#e8e4d9); display:flex; align-items:center; padding:0 20px; gap:12px; z-index:10;">
        <!-- Hamburger -->
        <button
          v-if="isMobile"
          @click="sidebarOpen = !sidebarOpen"
          style="width:36px; height:36px; border:none; border-radius:9px; background:transparent; cursor:pointer; display:flex; align-items:center; justify-content:center; color:var(--text,#1f2a24);"
        >
          <i class="fa-solid fa-bars" style="font-size:16px;"></i>
        </button>
        <span style="font-size:15px; font-weight:700; color:var(--text,#1f2a24);">Green Valley Farm</span>
        <div style="flex:1;"></div>
        <!-- Clock -->
        <span style="font-size:13px; font-weight:600; color:var(--muted,#73817a); font-family:'JetBrains Mono',monospace;">{{ clock }}</span>
        <!-- Dark toggle -->
        <button
          @click="dark = !dark"
          style="width:36px; height:36px; border:1px solid var(--border,#e8e4d9); border-radius:9px; background:transparent; cursor:pointer; display:flex; align-items:center; justify-content:center; color:var(--muted,#73817a); transition:background .15s;"
          onmouseover="this.style.background='var(--hover,#f1f5f1)'"
          onmouseout="this.style.background='transparent'"
        >
          <i :class="dark ? 'fa-solid fa-sun' : 'fa-solid fa-moon'" style="font-size:14px;"></i>
        </button>
        <!-- Bell -->
        <button
          style="width:36px; height:36px; border:1px solid var(--border,#e8e4d9); border-radius:9px; background:transparent; cursor:pointer; display:flex; align-items:center; justify-content:center; color:var(--muted,#73817a); position:relative; transition:background .15s;"
          onmouseover="this.style.background='var(--hover,#f1f5f1)'"
          onmouseout="this.style.background='transparent'"
        >
          <i class="fa-solid fa-bell" style="font-size:14px;"></i>
          <span style="position:absolute; top:7px; right:7px; width:8px; height:8px; border-radius:50%; background:#E76F51; border:2px solid var(--topbar,#fff);"></span>
        </button>
        <!-- User avatar -->
        <NuxtLink
          to="/dashboard/profile"
          style="width:34px; height:34px; border-radius:50%; background:#2D6A4F; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; font-family:inherit; font-size:13px; font-weight:700; color:#fff; text-decoration:none; flex-shrink:0;"
        >{{ profile.initials }}</NuxtLink>
      </header>

      <!-- Scrollable main -->
      <main style="flex:1; overflow-y:auto; padding:24px;">
        <div style="max-width:1320px; margin:0 auto;">
          <slot />
        </div>
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
/** Dark mode flag — shared with all sub-pages via useState key 'wf-dark'. */
const dark = useState('wf-dark', () => false)

const sidebarOpen = ref(false)
const isMobile = ref(false)
const clock = ref('')

const auth = useAuthStore()

/** Log the user out and redirect to /auth. */
const handleLogout = () => auth.logout()

/** Initials and display name derived from the authenticated user. */
const profile = computed(() => {
  const u = auth.user
  const displayName = u?.name ?? 'User'
  const initials = displayName.split(' ').map((w: string) => w[0]).join('').toUpperCase().slice(0, 2)
  return { name: displayName, initials }
})

// ─── Clock ────────────────────────────────────────────────────────────────────
let clockTimer: ReturnType<typeof setInterval>
onMounted(() => {
  const tick = () => {
    const d = new Date()
    clock.value = d.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit', second: '2-digit' })
  }
  tick()
  clockTimer = setInterval(tick, 1000)
})
onUnmounted(() => clearInterval(clockTimer))

// ─── Mobile detection ─────────────────────────────────────────────────────────
const checkMobile = () => { isMobile.value = window.innerWidth < 900 }
onMounted(() => { checkMobile(); window.addEventListener('resize', checkMobile) })
onUnmounted(() => window.removeEventListener('resize', checkMobile))

// ─── Sidebar style ────────────────────────────────────────────────────────────
const sidebarStyle = computed((): string =>
  isMobile.value
    ? `width:248px; background:var(--side,#2D6A4F); display:flex; flex-direction:column; z-index:30; transition:transform .25s ease; position:fixed; top:0; bottom:0; left:0; transform:translateX(${sidebarOpen.value ? '0' : '-100%'}); box-shadow:0 0 40px rgba(0,0,0,0.2);`
    : `width:248px; flex-shrink:0; background:var(--side,#2D6A4F); display:flex; flex-direction:column; z-index:30; position:relative;`
)

// ─── Nav definitions ──────────────────────────────────────────────────────────
const navDefs = [
  { key: 'dashboard',   label: 'Dashboard',   icon: 'fa-solid fa-gauge-high' },
  { key: 'fields',      label: 'Fields',      icon: 'fa-solid fa-map' },
  { key: 'crops',         label: 'Crops',         icon: 'fa-solid fa-wheat-awn' },
  { key: 'encyclopedia',  label: 'Encyclopedia',  icon: 'fa-solid fa-book-open' },
  { key: 'plantations',   label: 'Plantations',   icon: 'fa-solid fa-tree' },
  { key: 'iot',           label: 'IoT Sensors',   icon: 'fa-solid fa-microchip', badge: '3' },
  { key: 'reports',     label: 'Reports',     icon: 'fa-solid fa-chart-column' },
  { key: 'settings',    label: 'Settings',    icon: 'fa-solid fa-gear' },
]

const route = useRoute()

/** Close the mobile sidebar when a nav link is clicked. */
const closeOnMobile = () => { if (isMobile.value) sidebarOpen.value = false }

/**
 * Determine whether a nav item is the currently active route.
 *
 * @param key - Nav item key matching the route segment
 * @returns True when the current path matches the item's route
 */
const isActive = (key: string): boolean =>
  key === 'dashboard'
    ? route.path === '/dashboard'
    : route.path.startsWith(`/dashboard/${key}`)

/**
 * Build the inline style string for a sidebar nav link.
 *
 * @param active - Whether the link represents the current route
 * @returns CSS style string
 */
const linkStyle = (active: boolean): string =>
  `width:100%; display:flex; align-items:center; gap:12px; padding:11px 14px; border-radius:11px; cursor:pointer; transition:background .15s; font-family:inherit; font-size:14px; font-weight:${active ? '600' : '500'}; background:${active ? 'rgba(132,169,140,0.32)' : 'transparent'}; color:${active ? '#fff' : 'var(--side-text,#dbe8df)'}; text-decoration:none;`
</script>

<style>
[data-theme="light"] {
  --bg: #F4F1EA; --card: #ffffff; --text: #1f2a24; --muted: #73817a;
  --border: #e8e4d9; --topbar: #ffffff; --hover: #f1f5f1;
  --side: #2D6A4F; --side-text: #dbe8df;
}
[data-theme="dark"] {
  --bg: #1a1a1a; --card: #242625; --text: #ececec; --muted: #9aa39d;
  --border: #343735; --topbar: #202221; --hover: #2d302e;
  --side: #16352a; --side-text: #bcd2c5;
}
* { box-sizing: border-box; }
html, body { margin: 0; padding: 0; height: 100%; }
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(14px); }
  to   { opacity: 1; transform: none; }
}
*::-webkit-scrollbar { width: 10px; height: 10px; }
*::-webkit-scrollbar-thumb { background: var(--border, #ddd); border-radius: 8px; }
</style>
