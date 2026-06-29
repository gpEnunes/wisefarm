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
        <button
          v-for="item in navDefs"
          :key="item.key"
          @click="nav = item.key; if (isMobile) sidebarOpen = false"
          :style="`width:100%; display:flex; align-items:center; gap:12px; padding:11px 14px; border:none; border-radius:11px; cursor:pointer; transition:background .15s; font-family:inherit; font-size:14px; font-weight:${nav === item.key ? '600' : '500'}; background:${nav === item.key ? 'rgba(132,169,140,0.32)' : 'transparent'}; color:${nav === item.key ? '#fff' : 'var(--side-text,#dbe8df)'};`"
        >
          <i :class="item.icon" style="font-size:15px; width:17px; text-align:center;"></i>
          <span style="flex:1; text-align:left;">{{ item.label }}</span>
          <span v-if="item.badge" style="font-size:11px; font-weight:700; padding:2px 7px; border-radius:99px; background:#E76F51; color:#fff;">{{ item.badge }}</span>
        </button>
      </nav>

      <!-- Farm info card -->
      <div style="padding:12px; flex-shrink:0;">
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
        <button
          @click="nav = 'profile'"
          style="width:34px; height:34px; border-radius:50%; background:#2D6A4F; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; font-family:inherit; font-size:13px; font-weight:700; color:#fff;"
        >{{ profile.initials }}</button>
      </header>

      <!-- Scrollable main -->
      <main style="flex:1; overflow-y:auto; padding:24px;">
        <div style="max-width:1320px; margin:0 auto; display:flex; flex-direction:column; gap:24px;">

          <!-- ─── DASHBOARD VIEW ─── -->
          <template v-if="nav === 'dashboard'">
            <!-- Page heading -->
            <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
              <div>
                <h1 style="font-size:22px; font-weight:800; margin:0 0 4px; letter-spacing:-0.4px;">Farm Dashboard</h1>
                <p style="font-size:14px; color:var(--muted,#73817a); margin:0;">Good morning, {{ profile.name.split(' ')[0] }} — here's your farm overview.</p>
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
          </template>

          <!-- ─── FIELDS VIEW ─── -->
          <template v-else-if="nav === 'fields'">
            <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
              <div>
                <h1 style="font-size:22px; font-weight:800; margin:0 0 4px; letter-spacing:-0.4px;">Farms &amp; Fields</h1>
                <p v-if="apiFarms.length > 0" style="font-size:14px; color:var(--muted,#73817a); margin:0;">{{ apiFarms.length }} farm{{ apiFarms.length !== 1 ? 's' : '' }} from your account.</p>
                <p v-else style="font-size:14px; color:var(--muted,#73817a); margin:0;">{{ fields.length }} fields monitored across Green Valley Farm.</p>
              </div>
              <button style="display:flex; align-items:center; gap:8px; padding:10px 18px; border:none; border-radius:10px; background:#2D6A4F; color:#fff; font-family:inherit; font-size:14px; font-weight:600; cursor:pointer;">
                <i class="fa-solid fa-plus"></i> Add field
              </button>
            </div>

            <!-- API farms -->
            <template v-if="apiFarms.length > 0">
              <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(320px,1fr)); gap:16px;">
                <div v-for="farm in apiFarms" :key="farm.id" style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:16px; padding:20px; transition:box-shadow .2s;" onmouseover="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.07)'" onmouseout="this.style.boxShadow='none'">
                  <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:14px;">
                    <div>
                      <div style="font-size:15px; font-weight:700; margin-bottom:3px;">{{ farm.name }}</div>
                      <div style="font-size:13px; color:var(--muted,#73817a);">{{ farm.location ?? '—' }}</div>
                    </div>
                    <span style="font-size:12px; font-weight:600; padding:4px 10px; border-radius:99px; background:rgba(82,183,136,0.15); color:#52B788;">Active</span>
                  </div>
                  <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                    <div style="background:var(--bg,#F4F1EA); border-radius:10px; padding:12px;">
                      <div style="font-size:11px; color:var(--muted,#73817a); font-weight:600; text-transform:uppercase; letter-spacing:.4px; margin-bottom:4px;">Total area</div>
                      <div style="font-size:16px; font-weight:700;">{{ farm.total_area_ha ?? '—' }} <span style="font-size:12px; font-weight:500; color:var(--muted,#73817a);">ha</span></div>
                    </div>
                    <div style="background:var(--bg,#F4F1EA); border-radius:10px; padding:12px;">
                      <div style="font-size:11px; color:var(--muted,#73817a); font-weight:600; text-transform:uppercase; letter-spacing:.4px; margin-bottom:4px;">Fields</div>
                      <div style="font-size:16px; font-weight:700;">{{ farm.fields_count ?? 0 }}</div>
                    </div>
                  </div>
                </div>
              </div>
            </template>

            <!-- Static fallback when API data not loaded yet -->
            <template v-else>
              <div v-if="farmsLoading" style="font-size:14px; color:var(--muted,#73817a); padding:20px 0;">Loading farms…</div>
              <div v-else style="display:grid; grid-template-columns:repeat(auto-fill,minmax(320px,1fr)); gap:16px;">
                <div v-for="field in fields" :key="field.name" style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:16px; padding:20px; transition:box-shadow .2s;" onmouseover="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.07)'" onmouseout="this.style.boxShadow='none'">
                  <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:14px;">
                    <div>
                      <div style="font-size:15px; font-weight:700; margin-bottom:3px;">{{ field.name }}</div>
                      <div style="font-size:13px; color:var(--muted,#73817a);">{{ field.crop }} · {{ field.area }} ha</div>
                    </div>
                    <span :style="`font-size:12px; font-weight:600; padding:4px 10px; border-radius:99px; background:${field.sb}; color:${field.sc};`">{{ field.status }}</span>
                  </div>
                  <div style="margin-bottom:8px;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                      <span style="font-size:13px; color:var(--muted,#73817a); font-weight:500;">Soil moisture</span>
                      <span :style="`font-size:13px; font-weight:700; color:${field.moistColor};`">{{ field.moist }}%</span>
                    </div>
                    <div style="height:6px; background:var(--bg,#F4F1EA); border-radius:99px; overflow:hidden;">
                      <div :style="`width:${field.moist}%; height:100%; border-radius:99px; background:${field.moistColor}; transition:width .5s ease;`"></div>
                    </div>
                  </div>
                </div>
              </div>
            </template>
          </template>

          <!-- ─── CROPS VIEW ─── -->
          <template v-else-if="nav === 'crops'">
            <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
              <div>
                <h1 style="font-size:22px; font-weight:800; margin:0 0 4px; letter-spacing:-0.4px;">Crops</h1>
                <p style="font-size:14px; color:var(--muted,#73817a); margin:0;">{{ crops.length }} crop types across all fields.</p>
              </div>
            </div>
            <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(300px,1fr)); gap:16px;">
              <div v-for="crop in crops" :key="crop.name" style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:16px; padding:20px;">
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
          </template>

          <!-- ─── PLANTATIONS VIEW ─── -->
          <template v-else-if="nav === 'plantations'">
            <div>
              <h1 style="font-size:22px; font-weight:800; margin:0 0 4px; letter-spacing:-0.4px;">Plantations</h1>
              <p style="font-size:14px; color:var(--muted,#73817a); margin:0 0 24px;">Active planting cycles and their progress.</p>
            </div>
            <div style="background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:18px; overflow:hidden;">
              <div style="padding:20px 22px; border-bottom:1px solid var(--border,#e8e4d9); display:grid; grid-template-columns:2fr 1fr 1fr 1fr 1fr 100px; gap:12px; font-size:12px; font-weight:600; color:var(--muted,#73817a); text-transform:uppercase; letter-spacing:.5px;">
                <span>Plot / Crop</span><span>Planted</span><span>Harvest</span><span>Progress</span><span>Status</span><span></span>
              </div>
              <div v-for="(p, i) in plantations" :key="p.plot" :style="`padding:18px 22px; display:grid; grid-template-columns:2fr 1fr 1fr 1fr 1fr 100px; gap:12px; align-items:center; ${i < plantations.length - 1 ? 'border-bottom:1px solid var(--border,#e8e4d9);' : ''} transition:background .15s;`" onmouseover="this.style.background='var(--hover,#f1f5f1)'" onmouseout="this.style.background='transparent'">
                <div>
                  <div style="font-size:14px; font-weight:600;">{{ p.crop }}</div>
                  <div style="font-size:12px; color:var(--muted,#73817a);">{{ p.plot }}</div>
                </div>
                <span style="font-size:13px; color:var(--muted,#73817a);">{{ p.planted }}</span>
                <span style="font-size:13px; color:var(--muted,#73817a);">{{ p.harvest }}</span>
                <div>
                  <div style="display:flex; align-items:center; gap:8px;">
                    <div style="flex:1; height:5px; background:var(--bg,#F4F1EA); border-radius:99px; overflow:hidden;">
                      <div :style="`width:${p.pct}%; height:100%; background:#2D6A4F; border-radius:99px;`"></div>
                    </div>
                    <span style="font-size:12px; font-weight:600; color:#2D6A4F; min-width:32px;">{{ p.pct }}%</span>
                  </div>
                </div>
                <span :style="`font-size:12px; font-weight:600; padding:4px 10px; border-radius:99px; background:${p.sb}; color:${p.sc};`">{{ p.status }}</span>
                <div style="display:flex; gap:6px; justify-content:flex-end;">
                  <button style="padding:6px 12px; border:1px solid var(--border,#e8e4d9); border-radius:8px; background:transparent; color:var(--text,#1f2a24); font-family:inherit; font-size:12px; cursor:pointer;">View</button>
                </div>
              </div>
            </div>
          </template>

          <!-- ─── IOT VIEW ─── -->
          <template v-else-if="nav === 'iot'">
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
          </template>

          <!-- ─── REPORTS VIEW ─── -->
          <template v-else-if="nav === 'reports'">
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
          </template>

          <!-- ─── SETTINGS VIEW ─── -->
          <template v-else-if="nav === 'settings'">
            <div>
              <h1 style="font-size:22px; font-weight:800; margin:0 0 4px; letter-spacing:-0.4px;">Settings</h1>
              <p style="font-size:14px; color:var(--muted,#73817a); margin:0 0 24px;">Manage your farm preferences and notifications.</p>
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
          </template>

          <!-- ─── PROFILE VIEW ─── -->
          <template v-else-if="nav === 'profile'">
            <div>
              <h1 style="font-size:22px; font-weight:800; margin:0 0 4px; letter-spacing:-0.4px;">Profile</h1>
              <p style="font-size:14px; color:var(--muted,#73817a); margin:0 0 24px;">Your account details and recent activity.</p>
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
                  <div style="display:flex; flex-direction:column; gap:14px;">
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
          </template>

        </div>
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Farm } from '~/types'

definePageMeta({ middleware: ['auth'] })

useHead({ title: 'WiseFarm — Dashboard' })

// ─── Auth ─────────────────────────────────────────────────────────────────────
const auth = useAuthStore()

/**
 * Log the user out and redirect to /auth.
 */
const handleLogout = () => auth.logout()

// ─── Core state ───────────────────────────────────────────────────────────────
const dark = ref(false)
const sidebarOpen = ref(false)
const nav = ref('dashboard')
const clock = ref('')
const isMobile = ref(false)

// ─── Clock ────────────────────────────────────────────────────────────────────
let timer: ReturnType<typeof setInterval>
onMounted(() => {
  const tick = () => {
    const d = new Date()
    clock.value = d.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit', second: '2-digit' })
  }
  tick()
  timer = setInterval(tick, 1000)
})
onUnmounted(() => clearInterval(timer))

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
  { key: 'dashboard', label: 'Dashboard', icon: 'fa-solid fa-gauge-high' },
  { key: 'fields',    label: 'Fields',    icon: 'fa-solid fa-map' },
  { key: 'crops',     label: 'Crops',     icon: 'fa-solid fa-wheat-awn' },
  { key: 'plantations', label: 'Plantations', icon: 'fa-solid fa-tree' },
  { key: 'iot',       label: 'IoT Sensors', icon: 'fa-solid fa-microchip', badge: '3' },
  { key: 'reports',   label: 'Reports',   icon: 'fa-solid fa-chart-column' },
  { key: 'settings',  label: 'Settings',  icon: 'fa-solid fa-gear' },
]

// ─── Chart.js ─────────────────────────────────────────────────────────────────
const lineCanvas = ref<HTMLCanvasElement | null>(null)
const barCanvas  = ref<HTMLCanvasElement | null>(null)
let lineChart: any = null
let barChart:  any = null

const buildCharts = async () => {
  if (!lineCanvas.value || !barCanvas.value) return
  const { Chart, registerables } = await import('chart.js')
  Chart.register(...registerables)

  const tc = dark.value
    ? { grid: 'rgba(255,255,255,0.06)', tick: '#9aa39d' }
    : { grid: 'rgba(0,0,0,0.05)',       tick: '#73817a' }

  // Line chart — soil moisture 24h
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

  // Bar chart — harvest forecast
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

onMounted(() => { if (nav.value === 'dashboard') buildCharts() })
watch([nav, dark], ([newNav]) => {
  if (newNav === 'dashboard') nextTick(() => buildCharts())
})
onUnmounted(() => { lineChart?.destroy(); barChart?.destroy() })

// ─── KPI cards ────────────────────────────────────────────────────────────────
const kpiDefs = [
  { id: 'fields',   label: 'Total Fields',       value: '24',    unit: '',   icon: 'fa-solid fa-vector-square',        color: '#2D6A4F', iconBg: 'rgba(45,106,79,0.12)',   trend: '+2',   trendIcon: 'fa-solid fa-arrow-up',   up: true,  alert: false },
  { id: 'alerts',   label: 'Active Alerts',       value: '3',     unit: '',   icon: 'fa-solid fa-triangle-exclamation', color: '#E76F51', iconBg: 'rgba(231,111,81,0.12)', trend: '+1',   trendIcon: 'fa-solid fa-arrow-up',   up: false, alert: true  },
  { id: 'harvest',  label: "Today's Harvest",     value: '1,840', unit: 'kg', icon: 'fa-solid fa-tractor',              color: '#40916C', iconBg: 'rgba(64,145,108,0.12)', trend: '+8%',  trendIcon: 'fa-solid fa-arrow-up',   up: true,  alert: false },
  { id: 'moisture', label: 'Soil Moisture Avg',   value: '62',    unit: '%',  icon: 'fa-solid fa-droplet',              color: '#52B788', iconBg: 'rgba(82,183,136,0.14)', trend: '-3%',  trendIcon: 'fa-solid fa-arrow-down', up: false, alert: false },
]
const activeKpi = ref('alerts')

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

// ─── API: Farms ───────────────────────────────────────────────────────────────
const api = useApi()
const apiFarms = ref<Farm[]>([])
const farmsLoading = ref(false)

/**
 * Load the authenticated user's farms from the API.
 * Skips if a request is already in flight.
 */
const loadFarms = async () => {
  if (farmsLoading.value) return
  farmsLoading.value = true
  try {
    const res = await api.get<{ data: Farm[] }>('/farms')
    apiFarms.value = res.data
  } catch {
    // keep empty — static fallback stays visible
  } finally {
    farmsLoading.value = false
  }
}

watch(nav, (v) => { if (v === 'fields') loadFarms() })
onMounted(() => { loadFarms() })

// ─── Fields (static fallback) ─────────────────────────────────────────────────
const fields = [
  { name: 'Field A — North Ridge',  crop: 'Wheat',  area: '18.5', moist: 72, status: 'Healthy',     sc: '#52B788', sb: 'rgba(82,183,136,0.15)'  },
  { name: 'Field B — South Slope',  crop: 'Corn',   area: '24.0', moist: 58, status: 'Healthy',     sc: '#52B788', sb: 'rgba(82,183,136,0.15)'  },
  { name: 'Field C — Riverside',    crop: 'Tomato', area: '9.2',  moist: 38, status: 'Needs water', sc: '#E76F51', sb: 'rgba(231,111,81,0.13)'  },
  { name: 'Field D — East Plateau', crop: 'Potato', area: '15.8', moist: 64, status: 'Healthy',     sc: '#52B788', sb: 'rgba(82,183,136,0.15)'  },
  { name: 'Field E — Hillside',     crop: 'Grapes', area: '12.4', moist: 49, status: 'Monitor',     sc: '#F4A261', sb: 'rgba(244,162,97,0.15)'  },
  { name: 'Field F — Lower Meadow', crop: 'Barley', area: '20.1', moist: 67, status: 'Healthy',     sc: '#52B788', sb: 'rgba(82,183,136,0.15)'  },
].map(f => ({ ...f, moistColor: f.moist < 45 ? '#E76F51' : f.moist < 55 ? '#F4A261' : '#52B788' }))

// ─── Crops ────────────────────────────────────────────────────────────────────
const crops = [
  { name: 'Wheat',  icon: 'fa-solid fa-wheat-awn',                     stage: 'Grain filling', pct: 78, area: '64 ha', yield: '42 t', health: 'Good',      hc: '#52B788', hb: 'rgba(82,183,136,0.15)'  },
  { name: 'Corn',   icon: 'fa-solid fa-seedling',                      stage: 'Vegetative',    pct: 45, area: '58 ha', yield: '58 t', health: 'Good',      hc: '#52B788', hb: 'rgba(82,183,136,0.15)'  },
  { name: 'Tomato', icon: 'fa-solid fa-apple-whole',                   stage: 'Flowering',     pct: 60, area: '22 ha', yield: '31 t', health: 'Monitor',   hc: '#F4A261', hb: 'rgba(244,162,97,0.15)'  },
  { name: 'Potato', icon: 'fa-solid fa-carrot',                        stage: 'Tuber growth',  pct: 70, area: '47 ha', yield: '47 t', health: 'Good',      hc: '#52B788', hb: 'rgba(82,183,136,0.15)'  },
  { name: 'Grapes', icon: 'fa-solid fa-wine-bottle',                   stage: 'Veraison',      pct: 85, area: '34 ha', yield: '24 t', health: 'Excellent', hc: '#2D6A4F', hb: 'rgba(45,106,79,0.13)'   },
  { name: 'Barley', icon: 'fa-solid fa-wheat-awn-circle-exclamation',  stage: 'Heading',       pct: 52, area: '40 ha', yield: '36 t', health: 'Good',      hc: '#52B788', hb: 'rgba(82,183,136,0.15)'  },
]

// ─── Plantations ──────────────────────────────────────────────────────────────
const plantations = [
  { plot: 'Plot 01 · North Ridge',  crop: 'Wheat',  planted: '12 Nov 2025', harvest: '20 Jul 2026', pct: 82, status: 'Maturing',    sc: '#52B788', sb: 'rgba(132,169,140,0.16)' },
  { plot: 'Plot 04 · Riverside',    crop: 'Tomato', planted: '04 Apr 2026', harvest: '15 Aug 2026', pct: 48, status: 'Growing',     sc: '#84A98C', sb: 'rgba(132,169,140,0.16)' },
  { plot: 'Plot 07 · East Plateau', crop: 'Potato', planted: '21 Mar 2026', harvest: '02 Sep 2026', pct: 55, status: 'Growing',     sc: '#84A98C', sb: 'rgba(132,169,140,0.16)' },
  { plot: 'Plot 09 · Hillside',     crop: 'Grapes', planted: '15 Mar 2024', harvest: '28 Sep 2026', pct: 90, status: 'Ripening',    sc: '#2D6A4F', sb: 'rgba(132,169,140,0.16)' },
  { plot: 'Plot 12 · South Slope',  crop: 'Corn',   planted: '02 May 2026', harvest: '10 Oct 2026', pct: 33, status: 'Early growth',sc: '#F4A261', sb: 'rgba(132,169,140,0.16)' },
]

// ─── Sensors ──────────────────────────────────────────────────────────────────
const sensors = [
  { id: 'SM-A01', type: 'Soil Moisture', icon: 'fa-solid fa-droplet',       loc: 'Field A', value: '72',  unit: '%',  battery: 88, online: true  },
  { id: 'TM-A02', type: 'Temperature',  icon: 'fa-solid fa-temperature-half', loc: 'Field A', value: '23.4',unit: '°C', battery: 64, online: true  },
  { id: 'SM-C04', type: 'Soil Moisture', icon: 'fa-solid fa-droplet',       loc: 'Field C', value: '—',   unit: '',   battery: 11, online: false },
  { id: 'PH-D01', type: 'Soil pH',      icon: 'fa-solid fa-flask-vial',     loc: 'Field D', value: '6.8', unit: 'pH', battery: 91, online: true  },
  { id: 'HU-E03', type: 'Humidity',     icon: 'fa-solid fa-cloud',          loc: 'Field E', value: '54',  unit: '%',  battery: 73, online: true  },
  { id: 'LX-B02', type: 'Light',        icon: 'fa-solid fa-sun',            loc: 'Field B', value: '48k', unit: 'lux',battery: 80, online: true  },
].map(s => ({
  ...s,
  statusColor: s.online ? '#52B788' : '#E76F51',
  statusBg:    s.online ? 'rgba(82,183,136,0.15)' : 'rgba(231,111,81,0.13)',
  statusLabel: s.online ? 'Online' : 'Offline',
  battColor:   s.battery < 20 ? '#E76F51' : s.battery < 50 ? '#F4A261' : '#52B788',
  battStyle:   `width:${s.battery}%; height:100%; border-radius:99px; background:${s.battery < 20 ? '#E76F51' : s.battery < 50 ? '#F4A261' : '#52B788'};`,
  valueColor:  s.online ? 'var(--text,#1f2a24)' : '#E76F51',
}))

// ─── Reports ──────────────────────────────────────────────────────────────────
const reports = [
  { name: 'Monthly Yield Summary',       type: 'Yield',   date: 'Jun 2026',      size: '2.4 MB', icon: 'fa-solid fa-chart-pie',   col: '#2D6A4F' },
  { name: 'Irrigation Efficiency Q2',    type: 'Water',   date: 'Jun 24, 2026',  size: '1.1 MB', icon: 'fa-solid fa-droplet',     col: '#52B788' },
  { name: 'Soil Health Analysis',        type: 'Soil',    date: 'Jun 18, 2026',  size: '3.7 MB', icon: 'fa-solid fa-layer-group', col: '#84A98C' },
  { name: 'Cost & Margin Report',        type: 'Finance', date: 'Jun 10, 2026',  size: '0.9 MB', icon: 'fa-solid fa-coins',       col: '#F4A261' },
  { name: 'Pest & Disease Log',          type: 'Health',  date: 'Jun 02, 2026',  size: '1.5 MB', icon: 'fa-solid fa-bug',         col: '#E76F51' },
]
const reportStats = [
  { label: 'Reports generated', value: '148', icon: 'fa-solid fa-file-lines'    },
  { label: 'This month',        value: '12',  icon: 'fa-solid fa-calendar-day'  },
  { label: 'Scheduled',         value: '4',   icon: 'fa-solid fa-clock'         },
]

// ─── Settings ─────────────────────────────────────────────────────────────────
const settings = reactive({ alertsEmail: true, alertsSms: false, weeklyReport: true, autoIrrigate: false, frost: true })
const toggleSetting = (key: keyof typeof settings) => { settings[key] = !settings[key] }

const settingsItems = [
  { key: 'alertsEmail',  label: 'Email alerts',       desc: 'Receive critical alerts by email' },
  { key: 'alertsSms',    label: 'SMS alerts',          desc: 'Receive urgent alerts by SMS'     },
  { key: 'weeklyReport', label: 'Weekly report',       desc: 'Auto-generate every Monday'       },
  { key: 'autoIrrigate', label: 'Auto irrigation',     desc: 'Trigger watering automatically'   },
  { key: 'frost',        label: 'Frost warnings',      desc: 'Alert when temperature < 5°C'     },
]
const accountFields = [
  { label: 'Full name',  type: 'text',  value: 'Amélie Rousseau'             },
  { label: 'Email',      type: 'email', value: 'a.rousseau@greenvalley.fr'   },
  { label: 'Farm name',  type: 'text',  value: 'Green Valley Farm'           },
  { label: 'Location',   type: 'text',  value: 'Provence, France'            },
]

// ─── Profile ──────────────────────────────────────────────────────────────────
const profile = computed(() => {
  const u = auth.user
  const displayName = u?.name ?? 'User'
  const initials = displayName.split(' ').map((w: string) => w[0]).join('').toUpperCase().slice(0, 2)
  return {
    name: displayName,
    initials,
    role: 'Agronomist',
    farm: apiFarms.value[0]?.name ?? 'My Farm',
    email: u?.email ?? '',
    stats: [
      { label: 'Fields managed', value: String(apiFarms.value.reduce((s: number, f: Farm) => s + (f.fields_count ?? 0), 0)) },
      { label: 'Farms', value: String(apiFarms.value.length) },
    ],
    details: [
      { label: 'Email', value: u?.email ?? '—', icon: 'fa-regular fa-envelope' },
      { label: 'Role', value: 'Agronomist', icon: 'fa-solid fa-user-shield' },
    ],
    activity: [] as { icon: string; col: string; text: string; time: string }[],
  }
})
</script>

<style scoped>
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
