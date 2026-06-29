/**
 * Route middleware that guards protected pages.
 *
 * Restores the token from localStorage on each navigation, then redirects
 * to /auth if no valid token is present.
 */
export default defineNuxtRouteMiddleware(async () => {
  const auth = useAuthStore()
  await auth.restoreFromStorage()
  if (!auth.isAuthenticated) {
    return navigateTo('/auth')
  }
})
