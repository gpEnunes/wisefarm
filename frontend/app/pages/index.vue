<template>
  <div style="font-family:'Inter',system-ui,sans-serif; background:#F4F1EA; color:#1f2a24; min-height:100vh;">

    <!-- Header -->
    <header style="position:sticky; top:0; z-index:100; background:#fff; border-bottom:1px solid #e8e4d9;">
      <div style="max-width:1200px; margin:0 auto; padding:0 28px; height:64px; display:flex; align-items:center; gap:16px;">
        <div style="display:flex; align-items:center; gap:10px;">
          <div style="width:34px; height:34px; border-radius:10px; background:#2D6A4F; display:flex; align-items:center; justify-content:center;">
            <i class="fa-solid fa-leaf" style="color:#fff; font-size:16px;"></i>
          </div>
          <span style="font-size:18px; font-weight:800; letter-spacing:-0.3px; color:#1f2a24;">WiseFarm</span>
        </div>
        <div style="flex:1;"></div>
        <nav style="display:flex; align-items:center; gap:6px;">
          <a href="#features" style="padding:8px 14px; border-radius:9px; font-size:14px; font-weight:500; color:#73817a; text-decoration:none; transition:color .15s;" onmouseover="this.style.color='#1f2a24'" onmouseout="this.style.color='#73817a'">Features</a>
          <a href="#how" style="padding:8px 14px; border-radius:9px; font-size:14px; font-weight:500; color:#73817a; text-decoration:none; transition:color .15s;" onmouseover="this.style.color='#1f2a24'" onmouseout="this.style.color='#73817a'">How it works</a>
          <div style="width:1px; height:20px; background:#e8e4d9; margin:0 4px;"></div>
          <NuxtLink to="/auth" style="padding:8px 16px; border-radius:9px; font-size:14px; font-weight:600; color:#2D6A4F; text-decoration:none; border:1.5px solid #2D6A4F; transition:all .18s;" onmouseover="this.style.background='#2D6A4F';this.style.color='#fff'" onmouseout="this.style.background='transparent';this.style.color='#2D6A4F'">Sign in</NuxtLink>
          <NuxtLink to="/auth" style="padding:8px 18px; border-radius:9px; font-size:14px; font-weight:600; color:#fff; text-decoration:none; background:#2D6A4F; transition:background .18s;" onmouseover="this.style.background='#40916C'" onmouseout="this.style.background='#2D6A4F'">Get started</NuxtLink>
        </nav>
      </div>
    </header>

    <!-- Hero -->
    <section style="max-width:1200px; margin:0 auto; padding:80px 28px 64px;">
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:64px; align-items:center;">
        <!-- Left copy -->
        <div style="animation:fadeUp .55s ease both;">
          <div style="display:inline-flex; align-items:center; gap:8px; padding:7px 14px; border-radius:99px; background:rgba(45,106,79,0.10); margin-bottom:24px;">
            <span style="width:7px; height:7px; border-radius:50%; background:#52B788; display:inline-block;"></span>
            <span style="font-size:13px; font-weight:600; color:#2D6A4F;">Smart precision farming</span>
          </div>
          <h1 style="font-size:52px; font-weight:800; line-height:1.08; letter-spacing:-1.5px; margin:0 0 22px; color:#1f2a24;">
            Farm smarter,<br><span style="color:#2D6A4F;">not harder</span>
          </h1>
          <p style="font-size:18px; color:#73817a; line-height:1.7; margin:0 0 36px; max-width:480px;">
            WiseFarm connects your IoT sensors, weather data and financial records into one platform — so every decision is backed by real numbers.
          </p>
          <div style="display:flex; gap:12px; flex-wrap:wrap;">
            <NuxtLink to="/auth" style="display:inline-flex; align-items:center; gap:10px; padding:15px 26px; border-radius:12px; background:#2D6A4F; color:#fff; font-size:15px; font-weight:700; text-decoration:none; transition:background .18s; box-shadow:0 4px 14px rgba(45,106,79,0.28);" onmouseover="this.style.background='#40916C'" onmouseout="this.style.background='#2D6A4F'">
              <i class="fa-solid fa-seedling"></i> Start for free
            </NuxtLink>
            <NuxtLink to="/dashboard" style="display:inline-flex; align-items:center; gap:10px; padding:15px 26px; border-radius:12px; background:#fff; color:#1f2a24; font-size:15px; font-weight:600; text-decoration:none; border:1.5px solid #e8e4d9; transition:border-color .18s;" onmouseover="this.style.borderColor='#84A98C'" onmouseout="this.style.borderColor='#e8e4d9'">
              <i class="fa-solid fa-gauge-high" style="color:#2D6A4F;"></i> Open the dashboard
            </NuxtLink>
          </div>
          <div style="display:flex; align-items:center; gap:20px; margin-top:36px;">
            <div style="display:flex; align-items:center; gap:6px;">
              <i class="fa-solid fa-circle-check" style="color:#52B788; font-size:13px;"></i>
              <span style="font-size:13.5px; color:#73817a;">No credit card</span>
            </div>
            <div style="display:flex; align-items:center; gap:6px;">
              <i class="fa-solid fa-circle-check" style="color:#52B788; font-size:13px;"></i>
              <span style="font-size:13.5px; color:#73817a;">14-day free trial</span>
            </div>
            <div style="display:flex; align-items:center; gap:6px;">
              <i class="fa-solid fa-circle-check" style="color:#52B788; font-size:13px;"></i>
              <span style="font-size:13.5px; color:#73817a;">Cancel anytime</span>
            </div>
          </div>
        </div>

        <!-- Right visual card -->
        <div style="animation:floaty 4s ease-in-out infinite; position:relative;">
          <div style="background:#fff; border-radius:20px; border:1px solid #e8e4d9; padding:28px; box-shadow:0 12px 48px rgba(0,0,0,0.08);">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
              <span style="font-size:15px; font-weight:700; color:#1f2a24;">Farm Overview</span>
              <span style="font-size:12px; color:#73817a; background:#F4F1EA; padding:4px 10px; border-radius:99px;">Live</span>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:20px;">
              <div v-for="kpi in heroKpis" :key="kpi.label" :style="`background:${kpi.bg}; border-radius:14px; padding:16px;`">
                <div style="font-size:22px; font-weight:800; margin-bottom:4px;" :style="`color:${kpi.col}`">{{ kpi.val }}</div>
                <div style="font-size:12px; color:#73817a; font-weight:500;">{{ kpi.label }}</div>
              </div>
            </div>
            <!-- Mini chart bar visual -->
            <div style="background:#F4F1EA; border-radius:12px; padding:14px;">
              <div style="font-size:12px; color:#73817a; font-weight:600; margin-bottom:10px; text-transform:uppercase; letter-spacing:.5px;">Soil moisture — 24h</div>
              <div style="display:flex; align-items:flex-end; gap:5px; height:48px;">
                <div v-for="(h, i) in miniChart" :key="i" :style="`flex:1; border-radius:4px 4px 0 0; background:${h > 60 ? '#52B788' : h > 48 ? '#F4A261' : '#E76F51'}; height:${h}%; opacity:0.85; transition:height .3s;`"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Stats bar -->
    <section style="background:#fff; border-top:1px solid #e8e4d9; border-bottom:1px solid #e8e4d9;">
      <div style="max-width:1200px; margin:0 auto; padding:24px 28px; display:grid; grid-template-columns:repeat(4,1fr); gap:0;">
        <div v-for="(stat, i) in stats" :key="stat.label" :style="`display:flex; flex-direction:column; align-items:center; padding:20px; ${i < 3 ? 'border-right:1px solid #e8e4d9;' : ''}`">
          <span style="font-size:30px; font-weight:800; color:#2D6A4F; letter-spacing:-1px;">{{ stat.val }}</span>
          <span style="font-size:13.5px; color:#73817a; font-weight:500; margin-top:4px;">{{ stat.label }}</span>
        </div>
      </div>
    </section>

    <!-- Features -->
    <section id="features" style="max-width:1200px; margin:0 auto; padding:80px 28px;">
      <div style="text-align:center; margin-bottom:52px;">
        <div style="display:inline-flex; align-items:center; gap:8px; padding:7px 14px; border-radius:99px; background:rgba(45,106,79,0.10); margin-bottom:16px;">
          <span style="font-size:13px; font-weight:600; color:#2D6A4F;">Everything you need</span>
        </div>
        <h2 style="font-size:36px; font-weight:800; letter-spacing:-0.8px; margin:0 0 14px;">Built for modern agriculture</h2>
        <p style="font-size:17px; color:#73817a; max-width:520px; margin:0 auto; line-height:1.65;">From IoT integration to financial analysis — WiseFarm gives you full visibility over every corner of your farm.</p>
      </div>
      <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:20px;">
        <div
          v-for="f in features"
          :key="f.title"
          style="background:#fff; border:1px solid #e8e4d9; border-radius:18px; padding:28px; transition:box-shadow .2s, transform .2s; cursor:default;"
          onmouseover="this.style.boxShadow='0 8px 32px rgba(0,0,0,0.08)'; this.style.transform='translateY(-3px)'"
          onmouseout="this.style.boxShadow='none'; this.style.transform='none'"
        >
          <div :style="`width:46px; height:46px; border-radius:13px; background:${f.bg}; display:flex; align-items:center; justify-content:center; margin-bottom:18px;`">
            <i :class="f.icon" :style="`color:${f.col}; font-size:19px;`"></i>
          </div>
          <h3 style="font-size:16px; font-weight:700; margin:0 0 10px; color:#1f2a24;">{{ f.title }}</h3>
          <p style="font-size:14px; color:#73817a; line-height:1.6; margin:0;">{{ f.desc }}</p>
        </div>
      </div>
    </section>

    <!-- How it works -->
    <section id="how" style="background:#fff; border-top:1px solid #e8e4d9; border-bottom:1px solid #e8e4d9;">
      <div style="max-width:1200px; margin:0 auto; padding:80px 28px;">
        <div style="text-align:center; margin-bottom:52px;">
          <h2 style="font-size:36px; font-weight:800; letter-spacing:-0.8px; margin:0 0 14px;">Up and running in minutes</h2>
          <p style="font-size:17px; color:#73817a; max-width:480px; margin:0 auto; line-height:1.65;">Three simple steps from first sensor to first insight.</p>
        </div>
        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:32px;">
          <div v-for="step in steps" :key="step.n" style="display:flex; flex-direction:column; align-items:center; text-align:center;">
            <div style="width:64px; height:64px; border-radius:18px; background:rgba(45,106,79,0.10); display:flex; align-items:center; justify-content:center; margin-bottom:20px;">
              <i :class="step.icon" style="color:#2D6A4F; font-size:24px;"></i>
            </div>
            <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; color:#84A98C; margin-bottom:10px; font-family:'JetBrains Mono',monospace;">{{ step.n }}</div>
            <h3 style="font-size:17px; font-weight:700; margin:0 0 10px; color:#1f2a24;">{{ step.title }}</h3>
            <p style="font-size:14.5px; color:#73817a; line-height:1.65; margin:0;">{{ step.desc }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section style="max-width:1200px; margin:0 auto; padding:80px 28px;">
      <div style="background:linear-gradient(135deg,#2D6A4F 0%,#1c4533 100%); border-radius:24px; padding:60px 48px; text-align:center; position:relative; overflow:hidden;">
        <div style="position:absolute; right:-60px; top:-60px; width:220px; height:220px; border-radius:50%; background:rgba(255,255,255,0.05);"></div>
        <div style="position:absolute; left:-40px; bottom:-40px; width:170px; height:170px; border-radius:50%; background:rgba(132,169,140,0.15);"></div>
        <div style="position:relative;">
          <h2 style="font-size:36px; font-weight:800; color:#fff; letter-spacing:-0.8px; margin:0 0 16px;">Ready to grow smarter?</h2>
          <p style="font-size:17px; color:rgba(255,255,255,0.7); max-width:420px; margin:0 auto 36px; line-height:1.65;">Join thousands of farmers already using WiseFarm to reduce waste and increase yield.</p>
          <NuxtLink to="/auth" style="display:inline-flex; align-items:center; gap:10px; padding:16px 30px; border-radius:12px; background:#fff; color:#2D6A4F; font-size:15px; font-weight:700; text-decoration:none; transition:transform .18s;" onmouseover="this.style.transform='scale(1.04)'" onmouseout="this.style.transform='none'">
            <i class="fa-solid fa-seedling"></i> Get started — it's free
          </NuxtLink>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer style="border-top:1px solid #e8e4d9; background:#fff;">
      <div style="max-width:1200px; margin:0 auto; padding:28px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
        <div style="display:flex; align-items:center; gap:10px;">
          <div style="width:28px; height:28px; border-radius:8px; background:#2D6A4F; display:flex; align-items:center; justify-content:center;">
            <i class="fa-solid fa-leaf" style="color:#fff; font-size:13px;"></i>
          </div>
          <span style="font-size:15px; font-weight:700; color:#1f2a24;">WiseFarm</span>
        </div>
        <span style="font-size:13px; color:#73817a;">© 2026 WiseFarm. All rights reserved.</span>
        <div style="display:flex; gap:20px;">
          <a href="#" style="font-size:13px; color:#73817a; text-decoration:none;" onmouseover="this.style.color='#1f2a24'" onmouseout="this.style.color='#73817a'">Privacy</a>
          <a href="#" style="font-size:13px; color:#73817a; text-decoration:none;" onmouseover="this.style.color='#1f2a24'" onmouseout="this.style.color='#73817a'">Terms</a>
          <a href="#" style="font-size:13px; color:#73817a; text-decoration:none;" onmouseover="this.style.color='#1f2a24'" onmouseout="this.style.color='#73817a'">Contact</a>
        </div>
      </div>
    </footer>

  </div>
</template>

<script setup lang="ts">
useHead({ title: 'WiseFarm — Smart precision farming' })

const heroKpis = [
  { val: '24', label: 'Active fields', col: '#2D6A4F', bg: 'rgba(45,106,79,0.09)' },
  { val: '62%', label: 'Soil moisture', col: '#52B788', bg: 'rgba(82,183,136,0.12)' },
  { val: '1,840 kg', label: "Today's harvest", col: '#40916C', bg: 'rgba(64,145,108,0.10)' },
  { val: '3', label: 'Active alerts', col: '#E76F51', bg: 'rgba(231,111,81,0.10)' },
]

const miniChart = [58, 62, 65, 63, 68, 71, 70, 66, 60, 55, 52, 58]

const stats = [
  { val: '12,000+', label: 'Farms using WiseFarm' },
  { val: '340 M', label: 'Sensor readings/day' },
  { val: '28%', label: 'Average water saving' },
  { val: '4.9 ★', label: 'Average rating' },
]

const features = [
  { icon: 'fa-solid fa-microchip', col: '#2D6A4F', bg: 'rgba(45,106,79,0.10)', title: 'Live IoT monitoring', desc: 'Stream soil moisture, temperature, pH and light from every field in real time.' },
  { icon: 'fa-solid fa-chart-line', col: '#52B788', bg: 'rgba(82,183,136,0.14)', title: 'Predictive insights', desc: 'Forecast harvest yields and spot anomalies before they become losses.' },
  { icon: 'fa-solid fa-droplet', col: '#40916C', bg: 'rgba(64,145,108,0.12)', title: 'Smart irrigation', desc: 'Auto-trigger watering against thresholds and cut water use by up to a third.' },
  { icon: 'fa-solid fa-triangle-exclamation', col: '#E76F51', bg: 'rgba(231,111,81,0.12)', title: 'Proactive alerts', desc: 'Frost warnings, sensor faults and low-moisture flags pushed the moment they matter.' },
  { icon: 'fa-solid fa-coins', col: '#F4A261', bg: 'rgba(244,162,97,0.14)', title: 'Cost tracking', desc: 'Track inputs and margins per field so every euro is accounted for.' },
  { icon: 'fa-solid fa-file-lines', col: '#84A98C', bg: 'rgba(132,169,140,0.18)', title: 'One-click reports', desc: 'Generate yield, soil and finance reports ready to share with your team.' },
]

const steps = [
  { n: 'STEP 01', icon: 'fa-solid fa-satellite-dish', title: 'Connect sensors', desc: 'Pair your field devices in minutes — no technical setup required.' },
  { n: 'STEP 02', icon: 'fa-solid fa-brain', title: 'Analyze automatically', desc: 'WiseFarm fuses sensor, weather and cost data into clear signals.' },
  { n: 'STEP 03', icon: 'fa-solid fa-hand-pointer', title: 'Act with confidence', desc: 'Follow prioritized recommendations straight from your dashboard.' },
]
</script>

<style scoped>
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(18px); }
  to   { opacity: 1; transform: none; }
}
@keyframes floaty {
  0%, 100% { transform: translateY(0); }
  50%       { transform: translateY(-8px); }
}
* { box-sizing: border-box; }
</style>
