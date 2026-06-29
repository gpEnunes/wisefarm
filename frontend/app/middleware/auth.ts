/**
 * Route middleware that guards protected pages.
 *
 * Skips on the server — the token lives in localStorage which is not
 * available during SSR. The guard runs on every client-side navigation
 * and on the initial client hydration.
 */
export default defineNuxtRouteMiddleware(async () => {
  if (import.meta.server) return
  const auth = useAuthStore()
  await auth.restoreFromStorage()
  if (!auth.isAuthenticated) {
    return navigateTo('/auth')
  }
})
