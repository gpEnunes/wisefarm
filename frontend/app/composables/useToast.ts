import { ref } from 'vue'

type Toast = { id: number; message: string; type: 'success' | 'error' }

// Module-level ref: safe here because toasts are only triggered by user
// interaction (client-side). SSR never pushes toasts.
const toasts = ref<Toast[]>([])
let nextId = 0

/**
 * Composable for displaying temporary toast notifications.
 *
 * @returns `toasts` — reactive list consumed by AppToast, `show` — trigger function
 */
export const useToast = () => {
  /**
   * Push a toast notification that auto-dismisses after 3.5 seconds.
   *
   * @param message - Text to display
   * @param type    - Visual style: 'success' (green) or 'error' (orange-red)
   */
  const show = (message: string, type: 'success' | 'error' = 'success') => {
    const id = nextId++
    toasts.value.push({ id, message, type })
    setTimeout(() => { toasts.value = toasts.value.filter(t => t.id !== id) }, 3500)
  }

  return { toasts, show }
}
