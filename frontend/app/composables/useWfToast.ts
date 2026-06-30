import { ref } from 'vue'

type Toast = { id: number; message: string; type: 'success' | 'error' }

// Module-level: safe because toasts are only triggered by user interaction (client-side).
const toasts = ref<Toast[]>([])
let nextId = 0

/**
 * WiseFarm toast composable — separate from Nuxt UI's useToast.
 *
 * @returns `toasts` — reactive list consumed by AppToast, `show` — trigger function
 */
export const useWfToast = () => {
  /**
   * Push a toast that auto-dismisses after 3.5 seconds.
   *
   * @param message - Text to display
   * @param type    - 'success' (green) or 'error' (orange-red)
   */
  const show = (message: string, type: 'success' | 'error' = 'success') => {
    const id = nextId++
    toasts.value.push({ id, message, type })
    setTimeout(() => { toasts.value = toasts.value.filter(t => t.id !== id) }, 3500)
  }

  return { toasts, show }
}
