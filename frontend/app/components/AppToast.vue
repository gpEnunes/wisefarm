<template>
  <Teleport to="body">
    <div style="position:fixed;bottom:24px;right:24px;z-index:2000;display:flex;flex-direction:column;gap:10px;">
      <TransitionGroup name="toast">
        <div
          v-for="t in toasts"
          :key="t.id"
          :style="`padding:14px 20px;border-radius:12px;font-size:14px;font-weight:600;color:#fff;background:${t.type === 'success' ? '#2D6A4F' : '#E76F51'};box-shadow:0 4px 16px rgba(0,0,0,0.15);max-width:340px;`"
        >
          {{ t.message }}
        </div>
      </TransitionGroup>
    </div>
  </Teleport>
</template>

<script setup lang="ts">
/**
 * Renders the global toast queue produced by useToast().
 * Mount once in app.vue — works for all pages automatically.
 */
const { toasts } = useToast()
</script>

<style scoped>
.toast-enter-active, .toast-leave-active { transition: all 0.25s ease; }
.toast-enter-from, .toast-leave-to { opacity: 0; transform: translateX(20px); }
</style>
