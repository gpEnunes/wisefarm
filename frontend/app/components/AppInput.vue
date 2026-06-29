<template>
  <div style="display:flex;flex-direction:column;gap:6px;">
    <label v-if="label" style="font-size:13px;font-weight:600;color:#52635a;">{{ label }}</label>
    <input
      v-bind="$attrs"
      :value="modelValue"
      @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
      style="padding:11px 14px;border:1.5px solid #e0dccf;border-radius:10px;font-family:inherit;font-size:14px;background:#fff;color:#1f2a24;outline:none;width:100%;box-sizing:border-box;"
    />
    <span v-if="error" style="font-size:12px;color:#E76F51;">{{ error }}</span>
  </div>
</template>

<script setup lang="ts">
/**
 * Reusable labeled input with optional error message.
 *
 * @prop modelValue - Bound value (v-model)
 * @prop label - Input label text
 * @prop error - Validation error message
 * @emits update:modelValue - Emitted on every keystroke
 */
defineProps<{ modelValue?: string | number | null; label?: string; error?: string }>()
defineEmits(['update:modelValue'])
</script>
