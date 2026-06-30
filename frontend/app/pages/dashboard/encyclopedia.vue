<template>
  <div :data-theme="dark ? 'dark' : 'light'" style="display:flex; flex-direction:column; gap:24px; min-width:0;">

    <!-- Header -->
    <div>
      <h1 style="font-size:22px; font-weight:800; margin:0 0 4px; letter-spacing:-0.4px;">Encyclopedia</h1>
      <p style="font-size:14px; color:var(--muted,#73817a); margin:0;">
        Search the FAOSTAT crop database and import crops into your catalogue.
      </p>
    </div>

    <!-- Search input -->
    <div style="position:relative; max-width:520px;">
      <i class="fa-solid fa-magnifying-glass" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--muted,#73817a); font-size:14px; pointer-events:none;"></i>
      <input
        v-model="query"
        type="text"
        placeholder="Search crops (e.g. Tomatoes, Wheat, Rice…)"
        style="width:100%; box-sizing:border-box; padding:10px 14px 10px 38px; border:1px solid var(--border,#e8e4d9); border-radius:12px; font-size:14px; background:var(--card,#fff); color:inherit; outline:none;"
        @input="onInput"
      />
    </div>

    <!-- Error -->
    <div v-if="error" style="color:#e05252; font-size:14px;">{{ error }}</div>

    <!-- Loading -->
    <div v-if="loading" style="font-size:14px; color:var(--muted,#73817a);">Searching FAOSTAT…</div>

    <!-- Results -->
    <div v-else-if="results.length > 0" style="display:flex; flex-direction:column; gap:10px;">
      <div
        v-for="item in results"
        :key="item.faostat_code"
        style="display:flex; align-items:center; justify-content:space-between; gap:16px; background:var(--card,#fff); border:1px solid var(--border,#e8e4d9); border-radius:14px; padding:14px 18px;"
      >
        <div style="flex:1; min-width:0;">
          <div style="font-size:15px; font-weight:700;">{{ item.name }}</div>
          <div style="font-size:12px; color:var(--muted,#73817a);">FAOSTAT code {{ item.faostat_code }}</div>
        </div>
        <span
          v-if="item.already_imported"
          style="font-size:12px; font-weight:600; padding:5px 12px; border-radius:99px; background:rgba(82,183,136,0.15); color:#2D6A4F; white-space:nowrap;"
        >
          Already imported
        </span>
        <button
          v-else
          style="font-size:13px; font-weight:600; padding:7px 16px; border-radius:99px; background:#2D6A4F; color:#fff; border:none; cursor:pointer; white-space:nowrap;"
          @click="openImport(item)"
        >
          Import
        </button>
      </div>
    </div>

    <!-- Empty state (after search) -->
    <div
      v-else-if="searched && !loading"
      style="text-align:center; padding:48px 0; color:var(--muted,#73817a); font-size:14px;"
    >
      No crops found for "{{ query }}" in the FAOSTAT database.
    </div>

    <!-- Default state (before search) -->
    <div
      v-else-if="!searched"
      style="text-align:center; padding:48px 0; color:var(--muted,#73817a); font-size:14px;"
    >
      Type at least 2 characters to search the FAOSTAT crop catalogue.
    </div>

    <!-- Import confirmation modal -->
    <AppModal :model-value="!!importTarget" :title="importTarget ? `Import ${importTarget.name}` : ''" @update:model-value="importTarget = null">
      <p style="font-size:14px; color:var(--muted,#73817a); margin:0 0 20px;">
        This will import <strong>{{ importTarget.name }}</strong> (FAOSTAT code {{ importTarget.faostat_code }})
        into your catalogue, including global yield benchmarks for 2013–2023.
      </p>
      <div style="display:flex; gap:10px; justify-content:flex-end;">
        <button
          style="font-size:13px; font-weight:600; padding:8px 18px; border-radius:99px; border:1px solid var(--border,#e8e4d9); background:transparent; cursor:pointer; color:inherit;"
          @click="importTarget = null"
        >
          Cancel
        </button>
        <button
          :disabled="importing"
          style="font-size:13px; font-weight:600; padding:8px 18px; border-radius:99px; background:#2D6A4F; color:#fff; border:none; cursor:pointer;"
          @click="confirmImport"
        >
          {{ importing ? 'Importing…' : 'Import' }}
        </button>
      </div>
    </AppModal>

  </div>
</template>

<script setup lang="ts">
import type { FaostatItem } from '~/types'

definePageMeta({ layout: 'dashboard', middleware: ['auth'], ssr: false })
useHead({ title: 'WiseFarm — Encyclopedia' })

const dark = useState('wf-dark', () => false)
const api = useApi()
const toast = useWfToast()

const query = ref('')
const results = ref<FaostatItem[]>([])
const loading = ref(false)
const searched = ref(false)
const error = ref('')
const importTarget = ref<FaostatItem | null>(null)
const importing = ref(false)

let debounceTimer: ReturnType<typeof setTimeout> | null = null

const onInput = () => {
  if (debounceTimer) clearTimeout(debounceTimer)
  debounceTimer = setTimeout(search, 400)
}

const search = async () => {
  const q = query.value.trim()
  if (q.length < 2) {
    results.value = []
    searched.value = false
    return
  }

  loading.value = true
  error.value = ''
  try {
    const res = await api.get<{ data: FaostatItem[] }>(`/encyclopedia/search?q=${encodeURIComponent(q)}`)
    results.value = res.data
    searched.value = true
  } catch {
    error.value = 'Failed to reach the FAOSTAT service. Please try again.'
  } finally {
    loading.value = false
  }
}

const openImport = (item: FaostatItem) => {
  importTarget.value = item
}

const confirmImport = async () => {
  if (!importTarget.value) return
  importing.value = true
  try {
    await api.post('/encyclopedia/import', {
      faostat_item_code: importTarget.value.faostat_code,
      name: importTarget.value.name,
    })
    toast.show(`${importTarget.value.name} imported successfully.`, 'success')
    const code = importTarget.value.faostat_code
    results.value = results.value.map(r =>
      r.faostat_code === code ? { ...r, already_imported: true } : r
    )
    importTarget.value = null
  } catch {
    toast.show('Import failed. Please try again.', 'error')
  } finally {
    importing.value = false
  }
}
</script>
