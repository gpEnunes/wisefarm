import { ref } from 'vue'

/**
 * Global singleton toast queue.
 * Defined at module level so all callers share the same reactive array.
 */
const toasts = ref<{ id: number; message: string; type: 'success' | 'error' }[]>([])
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
   * @param type - Visual style: 'success' (green) or 'error' (orange-red)
   */
  const show = (message: string, type: 'success' | 'error' = 'success') => {
    const id = nextId++
    toasts.value.push({ id, message, type })
    setTimeout(() => { toasts.value = toasts.value.filter(t => t.id !== id) }, 3500)
  }
  return { toasts, show }
}
