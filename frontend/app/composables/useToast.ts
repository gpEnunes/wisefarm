// useState ensures SSR-safe shared state — module-level ref would bleed between requests
let nextId = 0
type Toast = { id: number; message: string; type: 'success' | 'error' }

/**
 * Composable for displaying temporary toast notifications.
 *
 * @returns `toasts` — reactive list consumed by AppToast, `show` — trigger function
 */
export const useToast = () => {
  const toasts = useState<Toast[]>('toast-queue', () => [])

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
