<template>
  <Teleport to="body">
    <div v-if="modelValue" style="position:fixed;inset:0;z-index:1000;display:flex;align-items:center;justify-content:center;">
      <div @click="$emit('update:modelValue', false)" style="position:absolute;inset:0;background:rgba(0,0,0,0.45);"></div>
      <div style="position:relative;background:#fff;border-radius:16px;padding:32px;width:100%;max-width:480px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,0.18);">
        <button @click="$emit('update:modelValue', false)" style="position:absolute;top:16px;right:16px;background:none;border:none;cursor:pointer;font-size:18px;color:#73817a;">&#x2715;</button>
        <h3 v-if="title" style="margin:0 0 24px;font-size:20px;font-weight:700;color:#1f2a24;">{{ title }}</h3>
        <slot />
      </div>
    </div>
  </Teleport>
</template>

<script setup lang="ts">
/**
 * Generic modal overlay using Vue Teleport.
 *
 * @prop modelValue - Controls visibility (v-model)
 * @prop title - Optional modal heading
 * @emits update:modelValue - Emitted when the backdrop or close button is clicked
 */
defineProps<{ modelValue: boolean; title?: string }>()
defineEmits(['update:modelValue'])
</script>
