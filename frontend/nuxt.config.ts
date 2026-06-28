// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2026-06-28',
  ssr: true,
  devtools: { enabled: true },

  modules: [
    '@pinia/nuxt',
    '@nuxt/ui',
  ],

  runtimeConfig: {
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE || 'http://localhost:8000/api',
    },
  },

  nitro: {
    preset: 'node-server',
  },
})
