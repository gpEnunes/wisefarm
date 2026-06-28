/**
 * Thin $fetch wrapper pre-configured with the backend API base URL.
 *
 * @returns Object with `get` and `post` helpers bound to the configured apiBase.
 */
export const useApi = () => {
  const config = useRuntimeConfig()

  return {
    /**
     * Perform a GET request against the API.
     *
     * @param endpoint - Path relative to apiBase (e.g. '/ping')
     * @param opts - Additional $fetch options
     */
    get: (endpoint: string, opts = {}) =>
      $fetch(endpoint, { baseURL: config.public.apiBase, ...opts }),

    /**
     * Perform a POST request against the API.
     *
     * @param endpoint - Path relative to apiBase (e.g. '/auth/login')
     * @param body - Request body
     * @param opts - Additional $fetch options
     */
    post: (endpoint: string, body: unknown, opts = {}) =>
      $fetch(endpoint, { method: 'POST', body, baseURL: config.public.apiBase, ...opts }),
  }
}
