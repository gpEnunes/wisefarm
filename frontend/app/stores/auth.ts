/**
 * Pinia store for authentication state.
 *
 * Manages the current user, bearer token, and authentication lifecycle
 * (login, register, logout). Token is persisted to localStorage under
 * the key `wf_token` so sessions survive page reloads.
 */

import type { AuthUser } from '~/types'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null as AuthUser | null,
    token: null as string | null,
  }),

  getters: {
    /**
     * Whether the user currently holds a valid token.
     *
     * @param state - Store state
     * @returns True when a token is present
     */
    isAuthenticated: (state): boolean => !!state.token,
  },

  actions: {
    /**
     * Authenticate an existing user and store the returned token.
     *
     * @param email - User email address
     * @param password - Plain-text password
     */
    async login(email: string, password: string) {
      const config = useRuntimeConfig()
      const res = await $fetch<{ token: string; user: AuthUser }>('/auth/login', {
        method: 'POST',
        baseURL: config.public.apiBase,
        body: { email, password },
      })
      this.token = res.token
      this.user = res.user
      if (import.meta.client) localStorage.setItem('wf_token', res.token)
    },

    /**
     * Register a new user account and store the returned token.
     *
     * @param name - Full display name
     * @param email - User email address
     * @param password - Plain-text password
     * @param password_confirmation - Password repeated for validation
     */
    async register(name: string, email: string, password: string, password_confirmation: string) {
      const config = useRuntimeConfig()
      const res = await $fetch<{ token: string; user: AuthUser }>('/auth/register', {
        method: 'POST',
        baseURL: config.public.apiBase,
        body: { name, email, password, password_confirmation },
      })
      this.token = res.token
      this.user = res.user
      if (import.meta.client) localStorage.setItem('wf_token', res.token)
    },

    /**
     * Revoke the current token on the server and clear local state.
     * Always navigates to /auth after completion, even if the API call fails.
     */
    async logout() {
      const config = useRuntimeConfig()
      try {
        await $fetch('/auth/logout', {
          method: 'POST',
          baseURL: config.public.apiBase,
          headers: { Authorization: `Bearer ${this.token}` },
        })
      } finally {
        this.token = null
        this.user = null
        if (import.meta.client) localStorage.removeItem('wf_token')
        await navigateTo('/auth')
      }
    },

    /**
     * Restore the token from localStorage on page load and rehydrate the
     * user object by calling /auth/me. Clears storage if the token is stale.
     * Call this from app.vue's onMounted hook.
     */
    async restoreFromStorage() {
      if (!import.meta.client) return
      const t = localStorage.getItem('wf_token')
      if (!t) return
      this.token = t
      try {
        const config = useRuntimeConfig()
        const user = await $fetch<AuthUser>('/auth/me', {
          baseURL: config.public.apiBase,
          headers: { Authorization: `Bearer ${t}` },
        })
        this.user = user
      } catch {
        // Token is stale or revoked — clear state
        this.token = null
        this.user = null
        localStorage.removeItem('wf_token')
      }
    },
  },
})
