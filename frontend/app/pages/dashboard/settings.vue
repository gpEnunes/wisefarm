<template>
  <div :data-theme="dark ? 'dark' : 'light'" style="display:flex; flex-direction:column; gap:24px; min-width:0;">

    <div>
      <h1 style="font-size:22px; font-weight:800; margin:0 0 4px; letter-spacing:-0.4px;">Settings</h1>
      <p style="font-size:14px; color:var(--muted,#73817a); margin:0;">Manage your farm preferences and notifications.</p>
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
      <!-- Notifications -->
      <div style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:18px; padding:24px;">
        <h3 style="font-size:15px; font-weight:700; margin:0 0 20px;">Notifications</h3>
        <div style="display:flex; flex-direction:column; gap:16px;">
          <div v-for="item in settingsItems" :key="item.key" style="display:flex; align-items:center; justify-content:space-between;">
            <div>
              <div style="font-size:14px; font-weight:600; margin-bottom:2px;">{{ item.label }}</div>
              <div style="font-size:12.5px; color:var(--muted,#73817a);">{{ item.desc }}</div>
            </div>
            <button
              @click="toggleSetting(item.key as keyof typeof settings)"
              :style="`width:44px; height:24px; border-radius:99px; border:none; cursor:pointer; position:relative; transition:background .2s; background:${settings[item.key as keyof typeof settings] ? '#2D6A4F' : 'var(--border,#e8e4d9)'};`"
            >
              <span :style="`position:absolute; top:3px; width:18px; height:18px; border-radius:50%; background:#fff; box-shadow:0 1px 3px rgba(0,0,0,0.2); transition:left .2s; left:${settings[item.key as keyof typeof settings] ? '23px' : '3px'};`"></span>
            </button>
          </div>
        </div>
      </div>

      <!-- Account -->
      <div style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:18px; padding:24px;">
        <h3 style="font-size:15px; font-weight:700; margin:0 0 20px;">Account</h3>
        <div style="display:flex; flex-direction:column; gap:14px;">
          <div v-for="field in accountFields" :key="field.label">
            <label style="font-size:13px; font-weight:600; color:var(--muted,#73817a); display:block; margin-bottom:6px;">{{ field.label }}</label>
            <input :type="field.type" :value="field.value" style="width:100%; padding:11px 14px; border:1px solid var(--border,#e8e4d9); border-radius:10px; background:var(--bg,#F4F1EA); color:var(--text,#1f2a24); font-family:inherit; font-size:14px; outline:none;" readonly />
          </div>
          <button style="margin-top:4px; padding:11px; border:none; border-radius:10px; background:#2D6A4F; color:#fff; font-family:inherit; font-size:14px; font-weight:600; cursor:pointer; transition:background .18s;" onmouseover="this.style.background='#40916C'" onmouseout="this.style.background='#2D6A4F'">Save changes</button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'dashboard', middleware: ['auth'], ssr: false })
useHead({ title: 'WiseFarm — Settings' })

const dark = useState('wf-dark', () => false)

const settings = reactive({ alertsEmail: true, alertsSms: false, weeklyReport: true, autoIrrigate: false, frost: true })

/**
 * Toggle a boolean settings key.
 *
 * @param key - Key of the settings object to flip
 */
const toggleSetting = (key: keyof typeof settings) => { settings[key] = !settings[key] }

const settingsItems = [
  { key: 'alertsEmail',  label: 'Email alerts',   desc: 'Receive critical alerts by email' },
  { key: 'alertsSms',    label: 'SMS alerts',      desc: 'Receive urgent alerts by SMS'     },
  { key: 'weeklyReport', label: 'Weekly report',   desc: 'Auto-generate every Monday'       },
  { key: 'autoIrrigate', label: 'Auto irrigation', desc: 'Trigger watering automatically'   },
  { key: 'frost',        label: 'Frost warnings',  desc: 'Alert when temperature < 5°C'     },
]

const accountFields = [
  { label: 'Full name',  type: 'text',  value: 'Amélie Rousseau'           },
  { label: 'Email',      type: 'email', value: 'a.rousseau@greenvalley.fr' },
  { label: 'Farm name',  type: 'text',  value: 'Green Valley Farm'         },
  { label: 'Location',   type: 'text',  value: 'Provence, France'          },
]
</script>
