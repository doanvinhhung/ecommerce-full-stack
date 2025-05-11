import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'

// https://vite.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    vueDevTools(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)),
      
    },
  },
  server: {
    watch: {
      usePolling: true,  // Cần thiết nếu dùng Docker/WSL
      interval: 1000
    }
  },
  optimizeDeps: {
    force: true  // Luôn tối ưu dependencies
  }
})
