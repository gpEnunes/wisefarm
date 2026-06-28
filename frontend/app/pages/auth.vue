<template>
  <div style="display:flex; height:100vh; font-family:'Inter',system-ui,sans-serif; overflow:hidden;">

    <!-- Left brand panel -->
    <div style="flex:0 0 46%; position:relative; overflow:hidden; display:flex; flex-direction:column; justify-content:space-between; padding:48px;">
      <!-- Gradient background -->
      <div style="position:absolute; inset:0; background:linear-gradient(150deg,#2D6A4F 0%,#235540 60%,#1c4533 100%);"></div>
      <!-- Decorative circles -->
      <div style="position:absolute; right:-80px; top:-60px; width:280px; height:280px; border-radius:50%; background:rgba(255,255,255,0.05);"></div>
      <div style="position:absolute; left:-60px; bottom:-40px; width:220px; height:220px; border-radius:50%; background:rgba(132,169,140,0.18);"></div>
      <!-- Content -->
      <div style="position:relative;">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:56px;">
          <div style="width:40px; height:40px; border-radius:12px; background:rgba(255,255,255,0.15); display:flex; align-items:center; justify-content:center;">
            <i class="fa-solid fa-leaf" style="color:#fff; font-size:18px;"></i>
          </div>
          <span style="font-size:20px; font-weight:800; color:#fff; letter-spacing:-0.3px;">WiseFarm</span>
        </div>
        <h2 style="font-size:34px; font-weight:800; color:#fff; line-height:1.15; letter-spacing:-0.8px; margin:0 0 18px;">Precision farming<br>starts here.</h2>
        <p style="font-size:16px; color:rgba(255,255,255,0.65); line-height:1.7; margin:0 0 44px; max-width:340px;">Connect your sensors, monitor your fields and make every decision with data — all from one place.</p>
        <!-- Feature bullets -->
        <div style="display:flex; flex-direction:column; gap:16px;">
          <div v-for="bullet in bullets" :key="bullet.text" style="display:flex; align-items:center; gap:14px;">
            <div :style="`width:36px; height:36px; border-radius:10px; background:${bullet.bg}; display:flex; align-items:center; justify-content:center; flex-shrink:0;`">
              <i :class="bullet.icon" :style="`color:${bullet.col}; font-size:14px;`"></i>
            </div>
            <span style="font-size:14.5px; color:rgba(255,255,255,0.80); font-weight:500;">{{ bullet.text }}</span>
          </div>
        </div>
      </div>
      <!-- Bottom trust -->
      <div style="position:relative; border-top:1px solid rgba(255,255,255,0.12); padding-top:24px;">
        <p style="font-size:13px; color:rgba(255,255,255,0.45); margin:0;">Trusted by 12,000+ farms worldwide · SOC 2 certified</p>
      </div>
    </div>

    <!-- Right form panel -->
    <div style="flex:1; background:#F4F1EA; display:flex; align-items:center; justify-content:center; padding:40px 32px; overflow-y:auto;">
      <div style="width:100%; max-width:400px;">
        <!-- Tab toggle -->
        <div style="display:flex; background:#e8e4d9; border-radius:13px; padding:4px; margin-bottom:32px; gap:4px;">
          <button :style="tabStyle(isLogin)" @click="mode = 'login'">Sign in</button>
          <button :style="tabStyle(!isLogin)" @click="mode = 'register'">Create account</button>
        </div>

        <!-- Form heading -->
        <Transition name="fade" mode="out-in">
          <div :key="mode">
            <h2 style="font-size:26px; font-weight:800; color:#1f2a24; margin:0 0 6px; letter-spacing:-0.4px;">{{ heading }}</h2>
            <p style="font-size:14.5px; color:#73817a; margin:0 0 28px; line-height:1.6;">{{ subheading }}</p>
          </div>
        </Transition>

        <!-- Form -->
        <form @submit.prevent="handleSubmit" style="display:flex; flex-direction:column; gap:16px;">
          <!-- Name field (register only) -->
          <Transition name="fade">
            <div v-if="!isLogin" style="position:relative;">
              <i class="fa-solid fa-user" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#84A98C; font-size:14px;"></i>
              <input type="text" placeholder="Full name" :style="inputStyle" />
            </div>
          </Transition>

          <!-- Email -->
          <div style="position:relative;">
            <i class="fa-solid fa-envelope" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#84A98C; font-size:14px;"></i>
            <input type="email" placeholder="Email address" :style="inputStyle" />
          </div>

          <!-- Password -->
          <div style="position:relative;">
            <i class="fa-solid fa-lock" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#84A98C; font-size:14px;"></i>
            <input type="password" placeholder="Password" :style="inputStyle" />
          </div>

          <!-- Confirm password (register only) -->
          <Transition name="fade">
            <div v-if="!isLogin" style="position:relative;">
              <i class="fa-solid fa-lock" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#84A98C; font-size:14px;"></i>
              <input type="password" placeholder="Confirm password" :style="inputStyle" />
            </div>
          </Transition>

          <!-- Check + forgot -->
          <div style="display:flex; align-items:center; justify-content:space-between; margin:2px 0;">
            <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
              <input type="checkbox" style="accent-color:#2D6A4F; width:15px; height:15px;" />
              <span style="font-size:13.5px; color:#73817a;">{{ checkLabel }}</span>
            </label>
            <a v-if="isLogin" href="#" style="font-size:13.5px; color:#2D6A4F; text-decoration:none; font-weight:500;">Forgot password?</a>
          </div>

          <!-- Submit -->
          <button
            type="submit"
            style="margin-top:4px; width:100%; padding:14px; border:none; border-radius:12px; background:#2D6A4F; color:#fff; font-family:inherit; font-size:15px; font-weight:700; cursor:pointer; transition:background .18s; display:flex; align-items:center; justify-content:center; gap:9px;"
            onmouseover="this.style.background='#40916C'"
            onmouseout="this.style.background='#2D6A4F'"
          >
            <i :class="isLogin ? 'fa-solid fa-right-to-bracket' : 'fa-solid fa-user-plus'"></i>
            {{ submitLabel }}
          </button>

          <!-- Divider -->
          <div style="display:flex; align-items:center; gap:12px; margin:4px 0;">
            <div style="flex:1; height:1px; background:#e0dccf;"></div>
            <span style="font-size:13px; color:#84A98C; font-weight:500;">or continue with</span>
            <div style="flex:1; height:1px; background:#e0dccf;"></div>
          </div>

          <!-- Social buttons -->
          <div style="display:flex; gap:10px;">
            <button type="button" style="flex:1; padding:11px; border:1.5px solid #e0dccf; border-radius:11px; background:#fff; color:#1f2a24; font-family:inherit; font-size:13.5px; font-weight:600; cursor:pointer; transition:border-color .18s; display:flex; align-items:center; justify-content:center; gap:8px;" onmouseover="this.style.borderColor='#84A98C'" onmouseout="this.style.borderColor='#e0dccf'">
              <i class="fa-brands fa-google" style="color:#E76F51;"></i> Google
            </button>
            <button type="button" style="flex:1; padding:11px; border:1.5px solid #e0dccf; border-radius:11px; background:#fff; color:#1f2a24; font-family:inherit; font-size:13.5px; font-weight:600; cursor:pointer; transition:border-color .18s; display:flex; align-items:center; justify-content:center; gap:8px;" onmouseover="this.style.borderColor='#84A98C'" onmouseout="this.style.borderColor='#e0dccf'">
              <i class="fa-brands fa-microsoft" style="color:#2D6A4F;"></i> Microsoft
            </button>
          </div>
        </form>

        <!-- Switch mode -->
        <p style="text-align:center; margin-top:24px; font-size:14px; color:#73817a;">
          {{ switchText }}
          <button @click="mode = isLogin ? 'register' : 'login'" style="background:none; border:none; color:#2D6A4F; font-size:14px; font-weight:600; cursor:pointer; font-family:inherit; padding:0; margin-left:4px;">{{ switchAction }}</button>
        </p>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
useHead({ title: 'WiseFarm — Sign in' })

const mode = ref<'login' | 'register'>('login')
const isLogin = computed(() => mode.value === 'login')

const heading = computed(() => isLogin.value ? 'Welcome back' : 'Create your account')
const subheading = computed(() => isLogin.value ? 'Sign in to your WiseFarm workspace.' : 'Start your 14-day free trial — no card required.')
const checkLabel = computed(() => isLogin.value ? 'Remember me' : 'I agree to the terms')
const submitLabel = computed(() => isLogin.value ? 'Sign in' : 'Create account')
const switchText = computed(() => isLogin.value ? "Don't have an account?" : 'Already have an account?')
const switchAction = computed(() => isLogin.value ? 'Create one' : 'Sign in')

/**
 * Compute tab button style based on active state.
 *
 * @param active - Whether this tab is currently selected
 * @returns Inline style string
 */
const tabStyle = (active: boolean): string =>
  `flex:1; padding:10px; border:none; border-radius:9px; font-family:inherit; font-size:14px; font-weight:600; cursor:pointer; transition:all .18s; background:${active ? '#fff' : 'transparent'}; color:${active ? '#2D6A4F' : '#73817a'}; box-shadow:${active ? '0 1px 3px rgba(0,0,0,0.08)' : 'none'};`

const inputStyle = 'width:100%; padding:13px 14px 13px 40px; border:1px solid #e0dccf; border-radius:11px; font-family:inherit; font-size:14.5px; background:#fff; color:#1f2a24; outline:none;'

const handleSubmit = () => navigateTo('/dashboard')

const bullets = [
  { icon: 'fa-solid fa-microchip', col: '#74C69D', bg: 'rgba(116,198,157,0.20)', text: 'Real-time IoT sensor data from your fields' },
  { icon: 'fa-solid fa-chart-line', col: '#84A98C', bg: 'rgba(132,169,140,0.20)', text: 'Predictive yield forecasts and cost tracking' },
  { icon: 'fa-solid fa-droplet', col: '#52B788', bg: 'rgba(82,183,136,0.20)', text: 'Automated irrigation and proactive alerts' },
]
</script>

<style scoped>
* { box-sizing: border-box; }

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.18s ease, transform 0.18s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(6px);
}
</style>
