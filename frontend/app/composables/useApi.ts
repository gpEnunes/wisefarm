/**
 * Thin $fetch wrapper pre-configured with the backend API base URL
 * and Authorization header injection from the Pinia auth store.
 *
 * @returns Object with `get`, `post`, `put`, and `del` helpers.
 */

type FetchOpts = Parameters<typeof $fetch>[1]

export const useApi = () => {
  const config = useRuntimeConfig()
  const auth = useAuthStore()

  /**
   * Build the Authorization header when a token is present.
   *
   * @returns Record with Authorization header, or empty record
   */
  const headers = (): Record<string, string> =>
    auth.token ? { Authorization: `Bearer ${auth.token}` } : {}

  return {
    /**
     * Perform a GET request against the API.
     *
     * @param endpoint - Path relative to apiBase (e.g. '/farms')
     * @param opts - Additional $fetch options
     */
    get: <T = unknown>(endpoint: string, opts?: FetchOpts) =>
      $fetch<T>(endpoint, { baseURL: config.public.apiBase, headers: headers(), ...opts }),

    /**
     * Perform a POST request against the API.
     *
     * @param endpoint - Path relative to apiBase (e.g. '/auth/login')
     * @param body - Request body
     * @param opts - Additional $fetch options
     */
    post: <T = unknown>(endpoint: string, body: unknown, opts?: FetchOpts) =>
      $fetch<T>(endpoint, { method: 'POST', body, baseURL: config.public.apiBase, headers: headers(), ...opts }),

    /**
     * Perform a PUT request against the API.
     *
     * @param endpoint - Path relative to apiBase (e.g. '/farms/1')
     * @param body - Request body
     * @param opts - Additional $fetch options
     */
    put: <T = unknown>(endpoint: string, body: unknown, opts?: FetchOpts) =>
      $fetch<T>(endpoint, { method: 'PUT', body, baseURL: config.public.apiBase, headers: headers(), ...opts }),

    /**
     * Perform a DELETE request against the API.
     *
     * @param endpoint - Path relative to apiBase (e.g. '/farms/1')
     * @param opts - Additional $fetch options
     */
    del: (endpoint: string, opts?: FetchOpts) =>
      $fetch(endpoint, { method: 'DELETE', baseURL: config.public.apiBase, headers: headers(), ...opts }),
  }
}
